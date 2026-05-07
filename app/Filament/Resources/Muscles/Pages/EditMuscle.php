<?php

namespace App\Filament\Resources\Muscles\Pages;

use App\Filament\Resources\Muscles\MuscleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMuscle extends EditRecord
{
    protected static string $resource = MuscleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
