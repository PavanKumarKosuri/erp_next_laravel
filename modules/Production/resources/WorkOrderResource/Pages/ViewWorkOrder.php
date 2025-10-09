<?php

namespace Modules\Production\Resources\WorkOrderResource\Pages;

use Modules\Production\Resources\WorkOrderResource;
use Modules\Production\Models\WorkOrder;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Colors\Color;

class ViewWorkOrder extends ViewRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (WorkOrder $record) => $record->status === 'pending')
                ->action(fn (WorkOrder $record) => $record->update(['status' => 'approved'])),
            Actions\Action::make('start')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn (WorkOrder $record) => $record->status === 'approved')
                ->action(fn (WorkOrder $record) => $record->update([
                    'status' => 'in_progress',
                    'start_date' => now(),
                ])),
            Actions\Action::make('complete')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (WorkOrder $record) => $record->status === 'in_progress')
                ->action(fn (WorkOrder $record) => $record->update([
                    'status' => 'completed',
                    'actual_completion_date' => now(),
                ])),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Work Order Details')
                    ->schema([
                        TextEntry::make('wo_number')
                            ->label('WO Number')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('bom.name')
                            ->label('Bill of Materials'),

                        TextEntry::make('product.name')
                            ->label('Product'),

                        TextEntry::make('warehouse.name')
                            ->label('Warehouse'),

                        TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'secondary' => 'draft',
                                'warning' => 'pending',
                                'info' => 'approved',
                                'primary' => 'in_progress',
                                'success' => 'completed',
                                'danger' => 'cancelled',
                            ])
                            ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                        TextEntry::make('priority')
                            ->badge()
                            ->colors([
                                'secondary' => 'low',
                                'info' => 'medium',
                                'warning' => 'high',
                                'danger' => 'urgent',
                            ])
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                        TextEntry::make('user.name')
                            ->label('Assigned To'),

                        TextEntry::make('completion_percentage')
                            ->label('Progress')
                            ->suffix('%')
                            ->weight('bold'),
                    ])
                    ->columns(2),

                Section::make('Production Quantities')
                    ->schema([
                        TextEntry::make('planned_quantity')
                            ->label('Planned')
                            ->numeric(decimalPlaces: 2),

                        TextEntry::make('produced_quantity')
                            ->label('Produced')
                            ->numeric(decimalPlaces: 2)
                            ->weight('bold'),

                        TextEntry::make('rejected_quantity')
                            ->label('Rejected')
                            ->numeric(decimalPlaces: 2)
                            ->color('danger'),

                        TextEntry::make('remaining_quantity')
                            ->label('Remaining')
                            ->numeric(decimalPlaces: 2)
                            ->color('warning'),
                    ])
                    ->columns(4),

                Section::make('Schedule')
                    ->schema([
                        TextEntry::make('start_date')
                            ->date(),

                        TextEntry::make('end_date')
                            ->date(),

                        TextEntry::make('expected_completion_date')
                            ->date()
                            ->weight('bold'),

                        TextEntry::make('actual_completion_date')
                            ->date()
                            ->weight('bold'),
                    ])
                    ->columns(2),

                Section::make('Notes')
                    ->schema([
                        TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

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
