<?php

namespace App\Filament\Resources\ExerciseMedia\Pages;

use App\Filament\Resources\ExerciseMedia\ExerciseMediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExerciseMedia extends ListRecords
{
    protected static string $resource = ExerciseMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
