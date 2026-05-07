<?php

namespace App\Filament\Resources\ExerciseMedia;

use App\Filament\Resources\ExerciseMedia\Pages\CreateExerciseMedia;
use App\Filament\Resources\ExerciseMedia\Pages\EditExerciseMedia;
use App\Filament\Resources\ExerciseMedia\Pages\ListExerciseMedia;
use App\Filament\Resources\ExerciseMedia\Schemas\ExerciseMediaForm;
use App\Filament\Resources\ExerciseMedia\Tables\ExerciseMediaTable;
use App\Models\ExerciseMedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExerciseMediaResource extends Resource
{
    protected static ?string $model = ExerciseMedia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Exercise Media';

    protected static ?string $modelLabel = 'Exercise Media';

    protected static ?string $pluralModelLabel = 'Exercise Media';

    public static function form(Schema $schema): Schema
    {
        return ExerciseMediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExerciseMediaTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExerciseMedia::route('/'),
            'create' => CreateExerciseMedia::route('/create'),
            'edit' => EditExerciseMedia::route('/{record}/edit'),
        ];
    }
}
