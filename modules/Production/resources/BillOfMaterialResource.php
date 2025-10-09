<?php

namespace Modules\Production\Resources;

use Modules\Production\Resources\BillOfMaterialResource\Pages;
use Modules\Production\Models\BillOfMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;
use Filament\Forms\Set;

class BillOfMaterialResource extends Resource
{
    protected static ?string $model = BillOfMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Production';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Bill of Materials';

    protected static ?string $modelLabel = 'BOM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('BOM Information')
                    ->schema([
                        Forms\Components\TextInput::make('bom_number')
                            ->label('BOM Number')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generated')
                            ->helperText('Automatically generated on save'),

                        Forms\Components\Select::make('product_id')
                            ->label('Finished Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Standard Assembly'),

                        Forms\Components\TextInput::make('version')
                            ->required()
                            ->default('1.0')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Output Quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(0.01)
                            ->helperText('Quantity produced from this BOM'),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Components')
                    ->schema([
                        Forms\Components\Repeater::make('components')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Component')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if ($state) {
                                            $product = \Modules\Inventory\Models\Product::find($state);
                                            if ($product) {
                                                $set('unit_cost', $product->cost_price ?? 0);
                                            }
                                        }
                                    })
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty Needed')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(0.01)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateComponentTotal($set, $get)),

                                Forms\Components\TextInput::make('unit_cost')
                                    ->label('Unit Cost')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateComponentTotal($set, $get)),

                                Forms\Components\TextInput::make('scrap_percentage')
                                    ->label('Scrap %')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->helperText('Expected waste/loss percentage'),

                                Forms\Components\Placeholder::make('total_cost')
                                    ->label('Total')
                                    ->content(fn (Get $get): string => '$' . number_format($get('total_cost') ?? 0, 2)),

                                Forms\Components\TextInput::make('notes')
                                    ->columnSpan(2)
                                    ->placeholder('Component notes'),
                            ])
                            ->columns(8)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->addActionLabel('Add Component')
                            ->live(),
                    ]),
            ]);
    }

    protected static function calculateComponentTotal(Set $set, Get $get): void
    {
        $quantity = floatval($get('quantity') ?? 0);
        $unitCost = floatval($get('unit_cost') ?? 0);
        $total = $quantity * $unitCost;

        $set('total_cost', number_format($total, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bom_number')
                    ->label('BOM #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (BillOfMaterial $record): string => $record->product->name ?? ''),

                Tables\Columns\TextColumn::make('version')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Output Qty')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('components_count')
                    ->label('Components')
                    ->counts('components')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('total_component_cost')
                    ->label('Total Cost')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('unit_cost')
                    ->label('Unit Cost')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                SelectFilter::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('bom_number', 'desc');
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
            'index' => Pages\ListBillOfMaterials::route('/'),
            'create' => Pages\CreateBillOfMaterial::route('/create'),
            'view' => Pages\ViewBillOfMaterial::route('/{record}'),
            'edit' => Pages\EditBillOfMaterial::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()
            ->with(['product', 'components.product']);
    }
}
