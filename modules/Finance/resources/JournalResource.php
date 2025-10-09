<?php

namespace Modules\Finance\Resources;

use Modules\Finance\Resources\JournalResource\Pages;
use Modules\Finance\Models\Journal;
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

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Finance';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Journal Information')
                    ->schema([
                        Forms\Components\TextInput::make('journal_number')
                            ->label('Journal Number')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generated')
                            ->helperText('Automatically generated on save'),
                            
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->native(false),
                            
                        Forms\Components\TextInput::make('reference')
                            ->maxLength(100)
                            ->placeholder('e.g., INV-001, PO-002'),
                            
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'posted' => 'Posted',
                                'reversed' => 'Reversed',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false)
                            ->disabled(fn (?Journal $record) => $record && $record->status === 'posted'),
                            
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Section::make('Journal Entries')
                    ->schema([
                        Forms\Components\Repeater::make('journalEntries')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('account_id')
                                    ->label('Account')
                                    ->relationship('account', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(2)
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}"),
                                    
                                Forms\Components\TextInput::make('debit')
                                    ->label('Debit')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if ($state > 0) {
                                            $set('credit', 0);
                                        }
                                    }),

                                Forms\Components\TextInput::make('credit')
                                    ->label('Credit')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if ($state > 0) {
                                            $set('debit', 0);
                                        }
                                    }),
                                    
                                Forms\Components\TextInput::make('description')
                                    ->columnSpan(2)
                                    ->placeholder('Entry description'),
                            ])
                            ->columns(6)
                            ->defaultItems(2)
                            ->reorderable(false)
                            ->addActionLabel('Add Entry')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $totalDebit = collect($state)->sum('debit');
                                $totalCredit = collect($state)->sum('credit');
                                $set('total_debit_display', $totalDebit);
                                $set('total_credit_display', $totalCredit);
                            }),
                            
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Placeholder::make('total_debit_display')
                                    ->label('Total Debit')
                                    ->content(fn ($state) => '$' . number_format($state ?? 0, 2)),
                                    
                                Forms\Components\Placeholder::make('total_credit_display')
                                    ->label('Total Credit')
                                    ->content(fn ($state) => '$' . number_format($state ?? 0, 2)),
                                    
                                Forms\Components\Placeholder::make('difference')
                                    ->label('Difference')
                                    ->content(function (Get $get) {
                                        $debit = $get('total_debit_display') ?? 0;
                                        $credit = $get('total_credit_display') ?? 0;
                                        $diff = abs($debit - $credit);
                                        $color = $diff == 0 ? 'success' : 'danger';
                                        return new \Illuminate\Support\HtmlString(
                                            "<span class='text-{$color}-600 font-bold'>$" .
                                            number_format($diff, 2) .
                                            ($diff == 0 ? ' ✓' : ' ✗') .
                                            "</span>"
                                        );
                                    }),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('journal_number')
                    ->label('Journal #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->placeholder('—'),
                    
                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 40) {
                            return null;
                        }
                        return $state;
                    }),
                    
                Tables\Columns\TextColumn::make('total_debit')
                    ->label('Total Debit')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('total_credit')
                    ->label('Total Credit')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\IconColumn::make('is_balanced')
                    ->label('Balanced')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn (Journal $record) => $record->isBalanced()),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'posted' => 'success',
                        'reversed' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'posted' => 'Posted',
                        'reversed' => 'Reversed',
                    ])
                    ->multiple(),
                    
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('post')
                    ->label('Post')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Journal $record) => $record->status === 'draft' && $record->isBalanced())
                    ->action(function (Journal $record) {
                        $record->update(['status' => 'posted']);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Journal $record) => $record->status === 'draft'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('journal_number', 'desc');
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
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'view' => Pages\ViewJournal::route('/{record}'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['journalEntries.account', 'user']);
    }
}
