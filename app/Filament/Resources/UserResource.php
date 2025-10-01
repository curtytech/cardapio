<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Usuários';
    
    protected static ?string $modelLabel = 'Usuário';
    
    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->label('Função')
                            ->options([
                                'admin' => 'Administrador',
                                'manager' => 'Gerente',
                                'user' => 'Usuário',
                            ])
                            ->default('user')
                            ->required()
                            ->disabled(fn () => auth()->user()->role === 'user')
                            ->dehydrated(fn () => auth()->user()->role !== 'user'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Contato')
                    ->schema([
                        Forms\Components\TextInput::make('celphone')
                            ->label('Celular')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Endereço')
                    ->schema([
                        Forms\Components\TextInput::make('zipcode')
                            ->label('CEP')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label('Endereço')
                            ->maxLength(500),
                        Forms\Components\TextInput::make('number')
                            ->label('Número')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('complement')
                            ->label('Complemento')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('neighborhood')
                            ->label('Bairro')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->label('Cidade')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->label('Estado')
                            ->maxLength(255),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Redes Sociais')
                    ->schema([
                        Forms\Components\TextInput::make('instagram')
                            ->label('Instagram')
                            ->maxLength(255)
                            ->prefix('@'),
                        Forms\Components\TextInput::make('facebook')
                            ->label('Facebook')
                            ->maxLength(255)
                            ->url(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Imagens')
                    ->schema([
                        Forms\Components\FileUpload::make('image_logo')
                            ->label('Logo')
                            ->image()
                            ->directory('logos'),
                        Forms\Components\FileUpload::make('image_banner')
                            ->label('Banner')
                            ->image()
                            ->directory('banners'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Personalização')
                    ->schema([
                        Forms\Components\ColorPicker::make('color_primary')
                            ->label('Cor Primária')
                            ->default('#0000FF')
                            ->helperText('Cor principal do seu cardápio (títulos, ícones, etc.)'),
                        Forms\Components\ColorPicker::make('color_secondary')
                            ->label('Cor Secundária')
                            ->default('#8B5CF6')
                            ->helperText('Cor secundária para gradientes e detalhes'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_logo')
                    ->label('Logo')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Função')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'manager' => 'warning',
                        'user' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'manager' => 'Gerente',
                        'user' => 'Usuário',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('celphone')
                    ->label('Celular')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('Estado')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('instagram')
                    ->label('Instagram')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (?string $state): string => $state ? '@' . $state : ''),
                Tables\Columns\TextColumn::make('facebook')
                    ->label('Facebook')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn (?string $state): ?string => $state),
                Tables\Columns\ColorColumn::make('color_primary')
                    ->label('Cor Primária')
                    ->toggleable(),
                Tables\Columns\ColorColumn::make('color_secondary')
                    ->label('Cor Secundária')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Função')
                    ->options([
                        'admin' => 'Administrador',
                        'user' => 'Usuário',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Se o usuário logado for 'user', só pode ver seu próprio registro
        if (auth()->user()->role === 'user') {
            $query->where('id', auth()->id());
        }
        
        return $query;
    }
    
    public static function canCreate(): bool
    {
        // Apenas admins e managers podem criar usuários
        return in_array(auth()->user()->role, ['admin', 'manager']);
    }
    
    public static function canEdit($record): bool
    {
        // Admins e managers podem editar qualquer usuário
        if (in_array(auth()->user()->role, ['admin', 'manager'])) {
            return true;
        }
        
        // Usuários 'user' só podem editar seu próprio registro
        if (auth()->user()->role === 'user') {
            return $record->id === auth()->id();
        }
        
        return false;
    }
    
    public static function canDelete($record): bool
    {
        // Apenas admins podem deletar usuários
        if (auth()->user()->role === 'admin') {
            return true;
        }
        
        // Usuários não podem deletar nem mesmo seu próprio registro
        return false;
    }
    
    public static function canDeleteAny(): bool
    {
        // Apenas admins podem fazer delete em massa
        return auth()->user()->role === 'admin';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
