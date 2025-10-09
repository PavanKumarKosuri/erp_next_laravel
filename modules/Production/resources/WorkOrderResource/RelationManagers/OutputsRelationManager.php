<?php

namespace Modules\Production\Resources\WorkOrderResource\RelationManagers;

use Modules\Production\Models\ProductionOutput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OutputsRelationManager extends RelationManager
{
    protected static string $relationship = 'outputs';

    protected static ?string $title = 'Production Outputs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('production_date')
                    ->label('Production Date')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('quantity_produced')
                    ->label('Quantity Produced')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(1),

                Forms\Components\TextInput::make('quantity_rejected')
                    ->label('Quantity Rejected')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\TextInput::make('batch_number')
                    ->label('Batch Number')
                    ->maxLength(50),

                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('output_number')
            ->columns([
                Tables\Columns\TextColumn::make('output_number')
                    ->label('Output #')
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('production_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity_produced')
                    ->label('Produced')
                    ->numeric(decimalPlaces: 2),

                Tables\Columns\TextColumn::make('quantity_rejected')
                    ->label('Rejected')
                    ->numeric(decimalPlaces: 2)
                    ->color('danger'),

                Tables\Columns\TextColumn::make('quantity_accepted')
                    ->label('Accepted')
                    ->numeric(decimalPlaces: 2)
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('yield_percentage')
                    ->label('Yield %')
                    ->suffix('%')
                    ->color(fn ($state) => $state >= 95 ? 'success' : ($state >= 90 ? 'warning' : 'danger')),

                Tables\Columns\TextColumn::make('batch_number')
                    ->label('Batch')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Operator')
                    ->toggleable(),
            ])
            ->filters([
                //
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
            ->defaultSort('production_date', 'desc');
    }
}
