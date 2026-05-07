<?php

namespace App\Filament\Resources\ExerciseMedia\Pages;

use App\Filament\Resources\ExerciseMedia\ExerciseMediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExerciseMedia extends EditRecord
{
    protected static string $resource = ExerciseMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
