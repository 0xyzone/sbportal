<?php

namespace App\Filament\Imports;

use App\Models\Participant;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Notifications\Notification;
use Illuminate\Support\Number;

class ParticipantImporter extends Importer
{
    protected static ?string $model = Participant::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('full_name')
                ->requiredMapping()
                ->label('Full Name')
                ->example('Ram Shrestha')
                ->rules(['required', 'max:255']),
            ImportColumn::make('date_of_birth')
                ->example('"1 January 2005" or "2005-01-01"')
                ->label('Date of Birth (YYYY-MM-DD)')
                ->rules(['date']),
            ImportColumn::make('grade_level')
                ->example('10')
                ->label('Grade Level')
                ->rules(['max:255']),
            ImportColumn::make('gender')
                ->example('male')
                ->label('Gender')
                ->rules(['max:255']),
            ImportColumn::make('physical_address')
                ->example('Sorakhutte, Kathmandu, Nepal')
                ->label('Physical Address')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Participant
    {
        return new Participant();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your participant import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        Notification::make()
            ->title('Import Finished: ' . $import->successful_rows . ' Participants Added')
            ->body($body)
            ->success()
            ->sendToDatabase($import->user);

        return ''; // Return empty string to avoid duplicate notification
    }

    protected function beforeSave(): void
    {
        // Add the user_id here to ensure it exists before the first save
        $this->record->user_id = auth()->id();
        
        // Clean up the data
        if ($this->record->date_of_birth) {
            $this->record->date_of_birth = \Carbon\Carbon::parse($this->record->date_of_birth)->format('Y-m-d');
        }
        
        if ($this->record->gender) {
            $this->record->gender = strtolower($this->record->gender);
        }
    }
}
