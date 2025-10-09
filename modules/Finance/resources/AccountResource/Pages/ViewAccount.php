<?php

namespace Modules\Finance\Resources\AccountResource\Pages;

use Modules\Finance\Resources\AccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

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
                Infolists\Components\Section::make('Account Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'asset' => 'success',
                                'liability' => 'danger',
                                'equity' => 'info',
                                'revenue' => 'primary',
                                'expense' => 'warning',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        Infolists\Components\TextEntry::make('parent.name')
                            ->label('Parent Account')
                            ->placeholder('None (Top-level account)'),
                        Infolists\Components\TextEntry::make('balance')
                            ->money('USD')
                            ->color(fn ($state): string => $state >= 0 ? 'success' : 'danger')
                            ->weight('bold'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Sub-Accounts')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('children')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('code')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('name'),
                                Infolists\Components\TextEntry::make('type')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('balance')
                                    ->money('USD'),
                            ])
                            ->columns(4),
                    ])
                    ->visible(fn ($record) => $record->children->count() > 0),
            ]);
    }
}
