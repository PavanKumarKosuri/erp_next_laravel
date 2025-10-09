<?php

namespace Modules\CRM\Filament\Resources\CRMResource\Pages;

use Modules\CRM\Filament\Resources\CRMResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCRM extends EditRecord
{
    protected static string $resource = CRMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}