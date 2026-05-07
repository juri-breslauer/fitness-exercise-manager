<?php

namespace App\Filament\Pages;

use App\Services\ExerciseImportResult;
use App\Services\ExerciseImportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class ImportExercises extends Page
{
    protected static ?string $navigationLabel = 'Import Exercises';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Import Exercises';

    protected static ?string $slug = 'import-exercises';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.import-exercises';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $summary = null;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('dryRun')
                ->label('Dry Run')
                ->schema($this->uploadSchema())
                ->modalHeading('Preview exercise import')
                ->modalSubmitActionLabel('Run preview')
                ->action(fn (array $data) => $this->runImport($data, dryRun: true)),
            Action::make('import')
                ->label('Import JSON')
                ->schema($this->uploadSchema())
                ->modalHeading('Import exercises')
                ->modalDescription('This will create or update exercises, taxonomy, and pivot relationships from the uploaded JSON file.')
                ->modalSubmitActionLabel('Import')
                ->requiresConfirmation()
                ->action(fn (array $data) => $this->runImport($data, dryRun: false)),
        ];
    }

    /**
     * @return array<int, FileUpload>
     */
    protected function uploadSchema(): array
    {
        return [
            FileUpload::make('dataset')
                ->label('JSON dataset')
                ->acceptedFileTypes(['application/json', 'text/json', 'text/plain'])
                ->storeFiles(false)
                ->required(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function runImport(array $data, bool $dryRun): void
    {
        $json = $this->readUploadedFile($data['dataset'] ?? null);

        $result = app(ExerciseImportService::class)->importJson($json, $dryRun);
        $this->summary = $result->toArray();

        $this->notifyResult($result);
    }

    protected function readUploadedFile(mixed $file): string
    {
        if (is_object($file) && method_exists($file, 'getRealPath')) {
            return (string) file_get_contents($file->getRealPath());
        }

        if (is_string($file) && Storage::exists($file)) {
            return Storage::get($file);
        }

        return '';
    }

    protected function notifyResult(ExerciseImportResult $result): void
    {
        $hasErrors = $result->errors !== [];
        $title = $result->dryRun ? 'Import preview completed' : 'Import completed';

        Notification::make()
            ->title($title)
            ->body($hasErrors ? 'Some rows were skipped. Review the summary below.' : 'All valid rows were processed.')
            ->status($hasErrors ? 'warning' : 'success')
            ->send();
    }
}
