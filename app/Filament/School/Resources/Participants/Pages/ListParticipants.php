<?php

namespace App\Filament\School\Resources\Participants\Pages;

use App\Filament\Imports\ParticipantImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\School\Resources\Participants\ParticipantResource;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // \EightyNine\ExcelImport\ExcelImportAction::make()
            //     ->color("primary"),
            ImportAction::make()
                ->importer(ParticipantImporter::class)
                ->label('Import Participants from Excel')
                ->color('primary'),
            CreateAction::make()
                ->after(function ($record) {
                    $record->user_id = auth()->id();
                    $record->save();
                }),
        ];
    }
}
