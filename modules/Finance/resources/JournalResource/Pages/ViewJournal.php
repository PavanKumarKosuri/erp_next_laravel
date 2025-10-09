<?php

namespace Modules\Finance\Resources\JournalResource\Pages;

use Modules\Finance\Resources\JournalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewJournal extends ViewRecord
{
    protected static string $resource = JournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn ($record) => $record->status === 'draft'),
            Actions\Action::make('post')
                ->label('Post Journal')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'draft' && $record->isBalanced())
                ->action(function ($record) {
                    $record->update(['status' => 'posted']);
                    $this->refreshFormData(['status']);
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Journal Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('journal_number')
                            ->label('Journal Number')
                            ->copyable()
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('date')
                            ->date(),
                        Infolists\Components\TextEntry::make('reference')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'posted' => 'success',
                                'reversed' => 'danger',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Created By'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Journal Entries')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('journalEntries')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('account.code')
                                    ->label('Account Code')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('account.name')
                                    ->label('Account Name'),
                                Infolists\Components\TextEntry::make('debit')
                                    ->money('USD')
                                    ->color('success')
                                    ->weight(fn ($state) => $state > 0 ? 'bold' : 'normal'),
                                Infolists\Components\TextEntry::make('credit')
                                    ->money('USD')
                                    ->color('danger')
                                    ->weight(fn ($state) => $state > 0 ? 'bold' : 'normal'),
                                Infolists\Components\TextEntry::make('description')
                                    ->placeholder('—'),
                            ])
                            ->columns(5),
                    ]),

                Infolists\Components\Section::make('Totals')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_debit')
                                    ->label('Total Debit')
                                    ->money('USD')
                                    ->color('success')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('total_credit')
                                    ->label('Total Credit')
                                    ->money('USD')
                                    ->color('danger')
                                    ->weight('bold'),
                                Infolists\Components\IconEntry::make('is_balanced')
                                    ->label('Balanced')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger')
                                    ->getStateUsing(fn ($record) => $record->isBalanced()),
                            ]),
                    ]),
            ]);
    }
}
