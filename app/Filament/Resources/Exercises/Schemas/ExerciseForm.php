<?php

namespace App\Filament\Resources\Exercises\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExerciseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('display_name')
                    ->maxLength(255),
                TagsInput::make('aliases')
                    ->placeholder('Add alias'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TagsInput::make('instructions')
                    ->placeholder('Add instruction')
                    ->columnSpanFull(),
                TagsInput::make('tips')
                    ->placeholder('Add tip')
                    ->columnSpanFull(),
                Select::make('difficulty')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                Select::make('force')
                    ->options([
                        'push' => 'Push',
                        'pull' => 'Pull',
                        'static' => 'Static',
                    ]),
                Select::make('mechanic')
                    ->options([
                        'compound' => 'Compound',
                        'isolation' => 'Isolation',
                    ]),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->required()
                    ->default('published'),
            ]);
    }
}
