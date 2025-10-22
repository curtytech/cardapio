<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Net\MPSearchRequest;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Preference\Item;
use MercadoPago\Resources\Preference\BackUrls;
use MercadoPago\Resources\Preference\PaymentMethods;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken)) {
            throw new \Exception('Mercado Pago access token não configurado. Verifique as variáveis de ambiente.');
        }
        
        MercadoPagoConfig::setAccessToken($accessToken);
    }

    public function createPayment(Request $request, Reserva $reserva)
    {
        try {
            $event = $reserva->event;
            
            // Calcular valor total usando o preço do tipo de evento
            $valorPorPessoa = $event->tipo->preco;
            $valorTotal = $valorPorPessoa * $reserva->quantidade_pessoas;
            
            // Verificar se há desconto aplicável
            if ($event->tipo->temDesconto() && $reserva->quantidade_pessoas >= $event->tipo->quantidade_para_receber_desconto) {
                $valorPorPessoa = $event->tipo->preco_com_desconto;
                $valorTotal = $valorPorPessoa * $reserva->quantidade_pessoas;
            }
            
            // Atualizar reserva com valor
            $reserva->update(['valor_total' => $valorTotal]);
    
            $client = new PreferenceClient();
            
            // Criar preferência como array, não como objeto
            $preferenceData = [
                'items' => [
                    [
                        'title' => "Reserva - {$event->tipo->nome} - {$event->data_agendamento->format('d/m/Y')}",
                        'quantity' => 1,
                        'unit_price' => $valorTotal,
                        'currency_id' => 'BRL'
                    ]
                ],
                'external_reference' => (string)$reserva->id,
                'back_urls' => [
                    'success' => url("/pagamento/sucesso/{$reserva->id}"),
                    'failure' => url("/pagamento/falha/{$reserva->id}"),
                    'pending' => url("/pagamento/pendente/{$reserva->id}")
                ],                
                // 'auto_return' => 'approved',
                'payment_methods' => [
                    'excluded_payment_types' => [
                        [
                            'id' => 'ticket' // Exclui boleto bancário
                        ]
                    ],
                    'installments' => 12
                ],
                'notification_url' => env('MERCADOPAGO_WEBHOOK_URL', url('/webhook/mercadopago'))
            ];
            
            // REMOVER ESTA LINHA que está causando problemas:
            // var_dump($preferenceData);
            
            // Log dos dados que serão enviados para debug
            Log::info('Dados da preferência MercadoPago:', $preferenceData);
            
            $result = $client->create($preferenceData);
            
            // Salvar preference_id na reserva
            $reserva->update([
                'mercadopago_preference_id' => $result->id
            ]);
            
            return response()->json([
                'success' => true,
                'preference_id' => $result->id,
                'init_point' => $result->init_point,
                'sandbox_init_point' => $result->sandbox_init_point
            ]);
            
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            // Capturar erros específicos da API do MercadoPago
            $apiResponse = $e->getApiResponse();
            $responseContent = $apiResponse->getContent(); // Obter o conteúdo como array
            
            $errorDetails = [
                'status' => $responseContent['status'] ?? 'unknown',
                'message' => $e->getMessage(),
                'cause' => $responseContent['cause'] ?? [],
                'response' => $responseContent
            ];
            
            Log::error('Erro da API MercadoPago:', $errorDetails);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro da API MercadoPago: ' . $e->getMessage(),
                'details' => $errorDetails
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Erro geral ao criar pagamento MercadoPago: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pagamento no createPayment. Detalhes: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function paymentSuccess(Reserva $reserva)
    {
        return view('payment.success', compact('reserva'));
    }
    
    public function paymentFailure(Reserva $reserva)
    {
        return view('payment.failure', compact('reserva'));
    }
    
    public function paymentPending(Reserva $reserva)
    {
        return view('payment.pending', compact('reserva'));
    }
    
    public function verificarStatusPagamento($reservaId)
    {
        $reserva = Reserva::findOrFail($reservaId);
        
        if (!$reserva->mercadopago_preference_id) {
            return response()->json(['error' => 'Preference ID não encontrado']);
        }
        
        try {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
            
            // Buscar pagamentos pela external_reference
            $client = new PaymentClient();
            $searchRequest = [
                'external_reference' => (string)$reserva->id
            ];
            
            $payments = $client->search($searchRequest);
            
            Log::info('Verificação manual de pagamento:', [
                'reserva_id' => $reserva->id,
                'preference_id' => $reserva->mercadopago_preference_id,
                'payments_found' => $payments->results ? count($payments->results) : 0
            ]);
            
            if ($payments->results && count($payments->results) > 0) {
                $payment = $payments->results[0];
                
                // Atualizar reserva
                $statusAnterior = $reserva->status;
                $novoStatus = match($payment->status) {
                    'approved' => 'Pago',
                    'cancelled', 'rejected' => 'Cancelado',
                    default => 'Aguardando Pagamento'
                };
                
                $reserva->update([
                    'mercadopago_payment_id' => $payment->id,
                    'mercadopago_status' => $payment->status,
                    'mercadopago_response' => $payment->toArray(),
                    'status' => $novoStatus,
                    'data_pagamento' => $payment->status === 'approved' ? now() : null
                ]);
                
                Log::info('Status atualizado manualmente:', [
                    'reserva_id' => $reserva->id,
                    'status_anterior' => $statusAnterior,
                    'novo_status' => $novoStatus,
                    'payment_status' => $payment->status
                ]);
                
                return response()->json([
                    'status' => 'updated',
                    'payment_status' => $payment->status,
                    'reserva_status' => $novoStatus,
                    'message' => 'Status atualizado com sucesso!'
                ]);
            }
            
            return response()->json([
                'status' => 'no_payment_found',
                'message' => 'Nenhum pagamento encontrado para esta reserva'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do pagamento:', [
                'reserva_id' => $reservaId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Erro ao verificar pagamento: ' . $e->getMessage()]);
        }
    }

    public function consultarPagamento($data){
        
    }
    
    private function mapPaymentStatus($mpStatus)
    {
        return match($mpStatus) {
            'approved' => 'Pago',
            'cancelled', 'rejected' => 'Cancelado',
            default => 'Aguardando Pagamento'
        };
    }
}