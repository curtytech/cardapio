<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Produtos';

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $pluralModelLabel = 'Produtos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuário')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(auth()->id())
                            ->hidden(auth()->user()->role === 'user'),
                        Forms\Components\Select::make('category_id')
                            ->label('Categoria')
                            ->relationship('category', 'name', function ($query) {
                                // Se o usuário logado for 'user', só pode ver suas próprias categorias
                                if (auth()->user()->role === 'user') {
                                    $query->where('user_id', auth()->id());
                                }
                                return $query->where('is_active', true);
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('barcode')
                            ->label('Código de Barras')
                            ->maxLength(255)
                            ->hidden(auth()->user()->role === 'user'),
                        Forms\Components\RichEditor::make('description')
                            ->label('Descrição')
                            ->maxLength(500)
                            ->columnSpan('full'),
                    ])->columns(2),

                Forms\Components\Section::make('Preço e Status')
                    ->schema([
                        Forms\Components\TextInput::make('sell_price')
                            ->label('Preço de Venda')
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01)
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Ativo',
                                'inactive' => 'Inativo',
                                'out_of_stock' => 'Fora de Estoque',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Imagem e Características')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagem')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->imageEditor(),
                        FormActions::make([
                            Action::make('clearImage')
                                ->label('Apagar imagem')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->action(function (Set $set, Get $get) {
                                    $path = $get('image');
                                    if (!empty($path)) {
                                        try {
                                            if (Storage::disk('public')->exists($path)) {
                                                Storage::disk('public')->delete($path);
                                            }
                                        } catch (\Throwable $e) {
                                            // Ignora falhas de deleção no storage para garantir que o botão sempre funcione.
                                        }
                                    }
                                    $set('image', null);
                                }),
                        ]),
                        Forms\Components\TagsInput::make('features')
                            ->label('Características')
                            ->placeholder('Digite uma característica e pressione Enter')
                            ->suggestions([
                                'Vegetariano',
                                'Vegano',
                                'Sem Glúten',
                                'Sem Lactose',
                                'Picante',
                                'Orgânico',
                                'Artesanal',
                                'Promocional',
                                'Novo',
                                'Mais Vendido',
                                'Chef Especial',
                                'Low Carb',
                                'Fitness',
                                'Tradicional'
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem')
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->category?->color ?? 'gray'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sell_price')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'out_of_stock' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'out_of_stock' => 'Fora de Estoque',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    }),
                Tables\Columns\TextColumn::make('barcode')
                    ->label('Código')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('features')
                    ->label('Características')
                    ->badge()
                    ->separator(',')
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name', function ($query) {
                        // Se o usuário logado for 'user', só pode ver suas próprias categorias
                        if (auth()->user()->role === 'user') {
                            $query->where('user_id', auth()->id());
                        }
                        return $query->where('is_active', true);
                    })
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'out_of_stock' => 'Fora de Estoque',
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

        // Se o usuário logado for 'user', só pode ver seus próprios produtos
        if (auth()->user()->role === 'user') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function canEdit($record): bool
    {
        // Admins e managers podem editar qualquer produto
        if (in_array(auth()->user()->role, ['admin', 'manager'])) {
            return true;
        }

        // Usuários 'user' só podem editar seus próprios produtos
        if (auth()->user()->role === 'user') {
            return $record->user_id === auth()->id();
        }

        return false;
    }

    public static function canDelete($record): bool
    {
        // Admins podem deletar qualquer produto
        if (auth()->user()->role === 'admin') {
            return true;
        }

        // Managers e usuários 'user' só podem deletar seus próprios produtos
        if (in_array(auth()->user()->role, ['manager', 'user'])) {
            return $record->user_id === auth()->id();
        }

        return false;
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
