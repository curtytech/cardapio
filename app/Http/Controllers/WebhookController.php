<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Reserva;
use Exception;

class WebhookController extends Controller
{
    public function mercadoPago(Request $request)
    {
        try {
            // Log detalhado
            file_put_contents(
                public_path('webhook_debug.txt'),
                "[" . now() . "] \n" .
                "Method: " . $request->method() . "\n" .
                "Headers: " . json_encode($request->headers->all()) . "\n" .
                "Body: " . json_encode($request->all()) . "\n" .
                "========================\n\n",
                FILE_APPEND
            );

            // Temporariamente desabilitar validação de assinatura
            $validateSignature = env('MERCADOPAGO_VALIDATE_SIGNATURE', false);
            if ($validateSignature && !$this->validarAssinatura($request)) {
                Log::warning('Webhook MercadoPago: Assinatura inválida');
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Processar notificação
            $data = $request->all();

            // Verificar se é uma notificação de pagamento (corrigido)
            if (isset($data['topic']) && $data['topic'] === 'payment') {
                $paymentId = $data['id'] ?? $data['data']['id'] ?? null;

                if ($paymentId) {
                    Log::info('Processando pagamento', ['payment_id' => $paymentId]);
                    $this->processarPagamento($paymentId);
                } else {
                    Log::warning('Payment ID não encontrado na notificação', ['data' => $data]);
                }
            } else {
                Log::info('Tipo de notificação não processada', [
                    'topic' => $data['topic'] ?? 'unknown',
                    'type' => $data['type'] ?? 'unknown',
                    'data' => $data
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processado com sucesso',
                'timestamp' => now()
            ], 200);
        } catch (Exception $e) {
            Log::error('Erro no webhook MercadoPago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    private function validarAssinatura(Request $request)
    {
        try {
            // Verificar se a validação está habilitada
            $validateSignature = env('MERCADOPAGO_VALIDATE_SIGNATURE', true);
            if (!$validateSignature) {
                Log::info('Validação de assinatura desabilitada');
                return true;
            }

            $xSignature = $request->header('x-signature');
            $xRequestId = $request->header('x-request-id');

            if (!$xSignature || !$xRequestId) {
                Log::warning('Headers de assinatura ausentes', [
                    'x-signature' => $xSignature ? 'presente' : 'ausente',
                    'x-request-id' => $xRequestId ? 'presente' : 'ausente',
                    'all_headers' => $request->headers->all()
                ]);
                return false;
            }

            // Extrair timestamp e hash da assinatura
            $parts = explode(',', $xSignature);
            $timestamp = null;
            $hash = null;

            foreach ($parts as $part) {
                $part = trim($part);
                $keyValue = explode('=', $part, 2);
                if (count($keyValue) === 2) {
                    if ($keyValue[0] === 'ts') {
                        $timestamp = $keyValue[1];
                    } elseif ($keyValue[0] === 'v1') {
                        $hash = $keyValue[1];
                    }
                }
            }

            if (!$timestamp || !$hash) {
                Log::warning('Timestamp ou hash não encontrados na assinatura', [
                    'x_signature' => $xSignature,
                    'parts' => $parts
                ]);
                return false;
            }

            // Verificar se o timestamp não é muito antigo (5 minutos)
            $currentTime = time();
            if (abs($currentTime - intval($timestamp)) > 300) {
                Log::warning('Timestamp muito antigo', [
                    'timestamp' => $timestamp,
                    'current_time' => $currentTime,
                    'diff' => abs($currentTime - intval($timestamp))
                ]);
                return false;
            }

            // Construir string para validação
            $dataId = $request->input('data.id', '');
            $stringToValidate = "id:{$dataId};request-id:{$xRequestId};ts:{$timestamp};";

            // Calcular hash esperado
            $secret = env('MERCADOPAGO_WEBHOOK_SECRET');
            if (!$secret) {
                Log::error('MERCADOPAGO_WEBHOOK_SECRET não configurado');
                return false;
            }

            $expectedHash = hash_hmac('sha256', $stringToValidate, $secret);

            $isValid = hash_equals($expectedHash, $hash);

            Log::info('Validação de assinatura', [
                'string_to_validate' => $stringToValidate,
                'expected_hash' => $expectedHash,
                'received_hash' => $hash,
                'is_valid' => $isValid
            ]);

            return $isValid;
        } catch (Exception $e) {
            Log::error('Erro na validação de assinatura', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    private function processarPagamento($paymentId)
    {
        try {
            Log::info('Iniciando processamento do pagamento', ['payment_id' => $paymentId]);
            
            // Configurar access token
            $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
            if (!$accessToken) {
                Log::error('MERCADOPAGO_ACCESS_TOKEN não configurado');
                return;
            }
    
            // Buscar informações do pagamento na API do MercadoPago
            $url = "https://api.mercadopago.com/v1/payments/{$paymentId}";
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            Log::info('Resposta da API MercadoPago', [
                'payment_id' => $paymentId,
                'http_code' => $httpCode,
                'response_length' => strlen($response)
            ]);
    
            if ($httpCode !== 200) {
                Log::error('Erro ao buscar pagamento na API', [
                    'payment_id' => $paymentId,
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                return;
            }
    
            $paymentData = json_decode($response, true);
            if (!$paymentData) {
                Log::error('Erro ao decodificar resposta JSON', [
                    'payment_id' => $paymentId,
                    'response' => $response
                ]);
                return;
            }
    
            Log::info('Dados do pagamento obtidos', [
                'payment_id' => $paymentId,
                'status' => $paymentData['status'] ?? 'unknown',
                'external_reference' => $paymentData['external_reference'] ?? null,
                'payment_data' => $paymentData
            ]);
    
            // Verificar se tem external_reference (ID da reserva)
            $externalReference = $paymentData['external_reference'] ?? null;
            if (!$externalReference) {
                Log::warning('External reference não encontrada no pagamento', [
                    'payment_id' => $paymentId,
                    'payment_data' => $paymentData
                ]);
                return;
            }
    
            // Buscar reserva
            $reserva = Reserva::find($externalReference);
            if (!$reserva) {
                Log::warning('Reserva não encontrada', [
                    'reserva_id' => $externalReference,
                    'payment_id' => $paymentId
                ]);
                return;
            }
    
            Log::info('Reserva encontrada', [
                'reserva_id' => $reserva->id,
                'status_atual' => $reserva->status,
                'payment_id' => $paymentId
            ]);
    
            // Mapear status do MercadoPago para status da reserva
            $novoStatus = $this->mapPaymentStatus($paymentData['status']);
    
            if ($novoStatus && $reserva->status !== $novoStatus) {
                $statusAnterior = $reserva->status;
                $reserva->status = $novoStatus;
                $reserva->mercadopago_payment_id = $paymentId;
                $reserva->mercadopago_status = $paymentData['status'];
                $reserva->mercadopago_response = json_encode($paymentData);
                
                if ($novoStatus === 'Pago') {
                    $reserva->data_pagamento = now();
                }
                
                $reserva->save();
    
                Log::info('Status da reserva atualizado com sucesso', [
                    'reserva_id' => $reserva->id,
                    'status_anterior' => $statusAnterior,
                    'novo_status' => $novoStatus,
                    'payment_id' => $paymentId
                ]);
            } else {
                Log::info('Status não alterado', [
                    'reserva_id' => $reserva->id,
                    'status_atual' => $reserva->status,
                    'novo_status' => $novoStatus,
                    'payment_id' => $paymentId
                ]);
            }
        } catch (Exception $e) {
            Log::error('Erro ao processar pagamento', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function mapPaymentStatus($mercadoPagoStatus)
    {
        $statusMap = [
            'approved' => 'Pago',                   
            'pending' => 'Aguardando Pagamento',    
            'in_process' => 'Aguardando Pagamento', 
            'rejected' => 'Cancelado',              
            'cancelled' => 'Cancelado',             
            'refunded' => 'Cancelado',              
            'charged_back' => 'Cancelado'           
        ];
    
        return $statusMap[$mercadoPagoStatus] ?? null;
    }
}
