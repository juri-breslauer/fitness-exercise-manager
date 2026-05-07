<?php

namespace App\Filament\Resources\ExerciseMedia\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExerciseMediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('exercise_id')
                    ->relationship('exercise', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->options([
                        'image' => 'Image',
                        'gif' => 'GIF',
                        'video' => 'Video',
                    ])
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->maxLength(255),
                TextInput::make('disk')
                    ->maxLength(255),
                TextInput::make('path')
                    ->maxLength(255),
                TextInput::make('source')
                    ->maxLength(255),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_primary')
                    ->required(),
            ]);
    }
}
