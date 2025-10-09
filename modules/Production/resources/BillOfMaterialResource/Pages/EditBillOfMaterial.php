<?php

namespace Modules\Production\Resources\BillOfMaterialResource\Pages;

use Modules\Production\Resources\BillOfMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBillOfMaterial extends EditRecord
{
    protected static string $resource = BillOfMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
