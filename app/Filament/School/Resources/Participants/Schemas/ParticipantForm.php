<?php

namespace App\Filament\School\Resources\Participants\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'md' => 3])
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Participant Photo')
                        ->heading("")
                            ->schema([
                                FileUpload::make('image_path')
                                    ->image()
                                    ->imagePreviewHeight('100')
                                    ->optimize('jpg')
                                    ->resize(50)
                                    ->imageEditor()
                                    ->automaticallyOpenImageEditorForAspectRatio()
                                    ->label('Participant Photo')
                                    ->directory('participants/photos')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->openable()
                                    ->downloadable()
                                    ->previewable()
                                    ->required()
                                    ->columnSpanFull()
                                    ->imageAspectRatio('1:1')
                                    ->panelAspectRatio('1:1')
                                    ->panelLayout('integrated'),
                            ]),
                        Section::make('Participant Information')
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('full_name')
                                    ->required(),
                                DatePicker::make('date_of_birth')
                                    ->required()
                                    ->native(false),
                                Select::make('grade_level')
                                ->required()
                                    ->options([
                                        '1' => 'Grade 1',
                                        '2' => 'Grade 2',
                                        '3' => 'Grade 3',
                                        '4' => 'Grade 4',
                                        '5' => 'Grade 5',
                                        '6' => 'Grade 6',
                                        '7' => 'Grade 7',
                                        '8' => 'Grade 8',
                                        '9' => 'Grade 9',
                                        '10' => 'Grade 10',
                                        '11' => 'Grade 11',
                                        '12' => 'Grade 12',
                                    ]),
                                Radio::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other',
                                    ])
                                    ->inline()
                                    ->required(),
                            ]),
                    ]),
                Hidden::make('user_id')
                    ->default(fn() => auth()->id()),
                // Textarea::make('physical_address')
                //     ->rows(3)
                //     ->required()
                //     ->autosize()
                //     ->columnSpanFull(),
            ]);
    }
}
