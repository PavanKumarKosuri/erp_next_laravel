<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Facades\DB;

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->select('products.*')
                    ->join('stock_levels', 'products.id', '=', 'stock_levels.product_id')
                    ->whereRaw('stock_levels.quantity <= products.min_stock_level')
                    ->groupBy('products.id')
                    ->orderByRaw('(stock_levels.quantity / NULLIF(products.min_stock_level, 0))')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stockLevels.quantity')
                    ->label('Current Stock')
                    ->getStateUsing(fn ($record) => $record->stockLevels->sum('quantity'))
                    ->badge()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('min_stock_level')
                    ->label('Min Level')
                    ->badge()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unit'),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->heading('Low Stock Alert');
    }
}
