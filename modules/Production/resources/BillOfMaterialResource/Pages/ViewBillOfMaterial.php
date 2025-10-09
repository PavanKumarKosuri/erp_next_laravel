<?php

namespace Modules\Production\Resources\BillOfMaterialResource\Pages;

use Modules\Production\Resources\BillOfMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewBillOfMaterial extends ViewRecord
{
    protected static string $resource = BillOfMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('BOM Details')
                    ->schema([
                        TextEntry::make('bom_number')
                            ->label('BOM Number')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('product.name')
                            ->label('Finished Product'),

                        TextEntry::make('name')
                            ->label('BOM Name'),

                        TextEntry::make('version')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('quantity')
                            ->label('Output Quantity')
                            ->numeric(decimalPlaces: 2),

                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),

                        TextEntry::make('total_component_cost')
                            ->label('Total Component Cost')
                            ->money('USD')
                            ->weight('bold'),

                        TextEntry::make('unit_cost')
                            ->label('Unit Cost')
                            ->money('USD'),

                        TextEntry::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Components')
                    ->schema([
                        RepeatableEntry::make('components')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Component'),

                                TextEntry::make('quantity')
                                    ->label('Qty')
                                    ->numeric(decimalPlaces: 2),

                                TextEntry::make('unit_cost')
                                    ->label('Unit Cost')
                                    ->money('USD'),

                                TextEntry::make('scrap_percentage')
                                    ->label('Scrap %')
                                    ->suffix('%'),

                                TextEntry::make('total_cost')
                                    ->label('Total')
                                    ->money('USD')
                                    ->weight('bold'),

                                TextEntry::make('notes')
                                    ->columnSpanFull(),
                            ])
                            ->columns(5),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
