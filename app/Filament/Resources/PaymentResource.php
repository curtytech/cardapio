<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Pagamentos';

    protected static ?string $modelLabel = 'Pagamento';

    protected static ?string $pluralModelLabel = 'Pagamentos';

    public static function form(Form $form): Form
    {
        // Form não será usado (sem páginas de create/edit), mas mantemos básico por compatibilidade.
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Usuário')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('mercadopago_payment_id')
                    ->label('Pagamento ID')
                    ->maxLength(255),
                Forms\Components\TextInput::make('mercadopago_preference_id')
                    ->label('Preferência ID')
                    ->maxLength(255),
                Forms\Components\TextInput::make('mercadopago_status')
                    ->label('Status')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('data_pagamento')
                    ->label('Data do Pagamento'),
                Forms\Components\Textarea::make('mercadopago_response')
                    ->label('Resposta Mercado Pago')
                    ->rows(6),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mercadopago_payment_id')
                    ->label('Pagamento ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('mercadopago_preference_id')
                    ->label('Preferência ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('mercadopago_status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Aprovado',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('data_pagamento')
                    ->label('Data do Pagamento')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiration_date')
                    ->label('Expira em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('mercadopago_response')
                    ->label('Resposta')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('mercadopago_status')
                    ->label('Status')
                    ->options([
                        'approved' => 'Aprovado',
                        'pending' => 'Pendente',
                        'rejected' => 'Rejeitado',
                    ]),
            ])
            ->actions([
                // Sem actions (view/edit/delete) para focar apenas na listagem.
            ])
            ->bulkActions([
                // Sem bulk actions.
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Usuário comum vê apenas seus pagamentos
        if (auth()->user()?->role === 'user') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
        ];
    }
}