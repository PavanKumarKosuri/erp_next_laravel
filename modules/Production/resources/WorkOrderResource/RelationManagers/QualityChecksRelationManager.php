<?php

namespace Modules\Production\Resources\WorkOrderResource\RelationManagers;

use Modules\Production\Models\QualityCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QualityChecksRelationManager extends RelationManager
{
    protected static string $relationship = 'qualityChecks';

    protected static ?string $title = 'Quality Checks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('check_date')
                    ->label('Check Date')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('inspector')
                    ->required()
                    ->maxLength(100)
                    ->default(auth()->user()->name),

                Forms\Components\TextInput::make('sample_size')
                    ->label('Sample Size')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(10),

                Forms\Components\TextInput::make('passed_count')
                    ->label('Passed')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\TextInput::make('failed_count')
                    ->label('Failed')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\Select::make('status')
                    ->options([
                        'passed' => 'Passed',
                        'failed' => 'Failed',
                        'pending' => 'Pending Review',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\TagsInput::make('defect_types')
                    ->label('Defect Types')
                    ->placeholder('Add defect type')
                    ->helperText('Enter defect categories (e.g., "Scratch", "Dent", "Misalignment")')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('check_number')
            ->columns([
                Tables\Columns\TextColumn::make('check_number')
                    ->label('Check #')
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('check_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('inspector')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sample_size')
                    ->label('Sample')
                    ->numeric(),

                Tables\Columns\TextColumn::make('passed_count')
                    ->label('Passed')
                    ->numeric()
                    ->color('success'),

                Tables\Columns\TextColumn::make('failed_count')
                    ->label('Failed')
                    ->numeric()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('pass_rate')
                    ->label('Pass Rate')
                    ->suffix('%')
                    ->color(fn ($state) => $state >= 95 ? 'success' : ($state >= 90 ? 'warning' : 'danger'))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'passed',
                        'danger' => 'failed',
                        'warning' => 'pending',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'passed' => 'Passed',
                        'failed' => 'Failed',
                        'pending' => 'Pending Review',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
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
            ])
            ->defaultSort('check_date', 'desc');
    }
}
