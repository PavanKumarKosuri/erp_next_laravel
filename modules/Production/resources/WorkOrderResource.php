<?php

namespace Modules\Production\Resources;

use Modules\Production\Resources\WorkOrderResource\Pages;
use Modules\Production\Resources\WorkOrderResource\RelationManagers;
use Modules\Production\Models\WorkOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Colors\Color;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Production';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Work Orders';

    protected static ?string $modelLabel = 'Work Order';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Work Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('wo_number')
                            ->label('WO Number')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generated')
                            ->helperText('Automatically generated on save'),

                        Forms\Components\Select::make('bom_id')
                            ->label('Bill of Materials')
                            ->relationship('bom', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    $bom = \Modules\Production\Models\BillOfMaterial::find($state);
                                    if ($bom) {
                                        $set('product_id', $bom->product_id);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (Forms\Get $get) => !empty($get('bom_id'))),

                        Forms\Components\Select::make('warehouse_id')
                            ->label('Warehouse')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('planned_quantity')
                            ->label('Planned Quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(0.01),

                        Forms\Components\TextInput::make('produced_quantity')
                            ->label('Produced Quantity')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->disabled(),

                        Forms\Components\TextInput::make('rejected_quantity')
                            ->label('Rejected Quantity')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->default('medium')
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('Assigned To')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),

                Section::make('Schedule')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date'),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date'),

                        Forms\Components\DatePicker::make('expected_completion_date')
                            ->label('Expected Completion')
                            ->required(),

                        Forms\Components\DatePicker::make('actual_completion_date')
                            ->label('Actual Completion')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wo_number')
                    ->label('WO #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->description(fn (WorkOrder $record): string => $record->bom?->name ?? ''),

                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('planned_quantity')
                    ->label('Planned')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('produced_quantity')
                    ->label('Produced')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn (WorkOrder $record) => $record->produced_quantity >= $record->planned_quantity ? 'success' : 'warning'),

                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->color(fn ($state) => match (true) {
                        $state >= 100 => 'success',
                        $state >= 75 => 'info',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('status')
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

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'secondary' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('expected_completion_date')
                    ->label('Expected')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending Approval',
                        'approved' => 'Approved',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),

                SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->multiple(),

                SelectFilter::make('warehouse_id')
                    ->label('Warehouse')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (WorkOrder $record) => $record->status === 'pending')
                        ->action(fn (WorkOrder $record) => $record->update(['status' => 'approved'])),
                    Tables\Actions\Action::make('start')
                        ->icon('heroicon-o-play')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->visible(fn (WorkOrder $record) => $record->status === 'approved')
                        ->action(fn (WorkOrder $record) => $record->update([
                            'status' => 'in_progress',
                            'start_date' => now(),
                        ])),
                    Tables\Actions\Action::make('complete')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (WorkOrder $record) => $record->status === 'in_progress')
                        ->action(fn (WorkOrder $record) => $record->update([
                            'status' => 'completed',
                            'actual_completion_date' => now(),
                        ])),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('wo_number', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OutputsRelationManager::class,
            RelationManagers\QualityChecksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'view' => Pages\ViewWorkOrder::route('/{record}'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()
            ->with(['product', 'bom', 'warehouse', 'user']);
    }
}
