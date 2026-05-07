<?php

namespace App\Filament\Resources\Muscles\Pages;

use App\Filament\Resources\Muscles\MuscleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMuscles extends ListRecords
{
    protected static string $resource = MuscleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
