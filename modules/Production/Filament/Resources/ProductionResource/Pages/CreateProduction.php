<?php

namespace Modules\Production\Filament\Resources\ProductionResource\Pages;

use Modules\Production\Filament\Resources\ProductionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduction extends CreateRecord
{
    protected static string $resource = ProductionResource::class;
}