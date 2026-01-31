<?php

namespace App\Filament\School\Resources\Participants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->modifyQueryUsing(fn ($query) => $query
            ->where('user_id', auth()->id())
            )
            ->columns([
                TextColumn::make('participant_id')
                    ->label('ID'),
                ImageColumn::make('image_path')
                    ->disk('public')
                    ->label('Photo')
                    ->rounded()
                    ->imageGallery()
                    ->default(asset('images/default_avatar.png')),
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('grade_level')
                    ->formatStateUsing(fn(string $state): string => 'Grade ' . $state)
                    ->searchable(),
                TextColumn::make('gender')
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                'grade_level',
                'gender',
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
