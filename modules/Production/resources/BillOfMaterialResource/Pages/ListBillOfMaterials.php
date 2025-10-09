<?php

namespace Modules\Production\Resources\BillOfMaterialResource\Pages;

use Modules\Production\Resources\BillOfMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBillOfMaterials extends ListRecords
{
    protected static string $resource = BillOfMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
