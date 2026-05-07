<?php

namespace App\Filament\Resources\Muscles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MuscleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
