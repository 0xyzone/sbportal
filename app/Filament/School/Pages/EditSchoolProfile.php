<?php

namespace App\Filament\School\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\SchoolProfile;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class EditSchoolProfile extends Page
{
    public ?array $data = [];
    protected string $view = 'filament.school.pages.edit-school-profile';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Identification;
    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Grid::make(['default' => 1, 'lg' => 3])
                        ->schema([
                            // Left Column: Profile Picture & Core Info
                            Section::make('Profile Identity')
                                ->columnSpan(1)
                                ->schema([
                                    FileUpload::make('image_path')
                                        ->label('School Logo')
                                        ->image()
                                        ->optimize('jpg')
                                        ->resize(50)
                                        ->avatar()
                                        ->imageEditor()
                                        ->imageAspectRatio('1:1')
                                        ->automaticallyOpenImageEditorForAspectRatio()
                                        ->circleCropper() // Professional look for profile pics
                                        ->directory('profiles/images')
                                        ->maxSize(2048) // 2MB max size
                                        ->disk('public')
                                        ->visibility('public')
                                        // ->panelLayout('integrated')
                                        ->panelAspectRatio('1:1')
                                        ->columnSpanFull()
                                        ->openable()
                                        ->downloadable()
                                        ->previewable()
                                        ->movefiles()
                                        ->required(),

                                    TextInput::make('user_name') // Displaying Name/Email clearly
                                        ->label('School Name')
                                        ->placeholder(auth()->user()->name)
                                        ->disabled()
                                        ->suffixActions([
                                            Action::make('change_name')
                                                ->label('Change Name')
                                                ->url(route('filament.individual.auth.profile'))
                                                ->icon('heroicon-m-pencil-square')
                                                ->color('primary'),
                                        ]),
                                    TextInput::make('email') // Displaying Name/Email clearly
                                        ->label('School Email Address')
                                        ->placeholder(auth()->user()->email)
                                        ->disabled()
                                        ->suffixActions([
                                            Action::make('change_email')
                                                ->label('Change Email')
                                                ->url(route('filament.individual.auth.profile'))
                                                ->icon('heroicon-m-pencil-square')
                                                ->color('primary'),
                                        ]),
                                ]),

                            // Right Column: Tabbed Information
                            Tabs::make('Profile Details')
                                ->columnSpan(2)
                                ->persistTab()
                                ->tabs([
                                    Tab::make('Personal Info')
                                        ->icon('heroicon-m-user')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                TextInput::make('phone_number')
                                                    ->tel()
                                                    ->required(),
                                                TextInput::make('alternate_phone_number')
                                                    ->tel(),
                                            ]),

                                            Textarea::make('physical_address')
                                                ->rows(3)
                                                ->required()
                                                ->columnSpanFull(),
                                            TextInput::make('website_url')
                                                ->label('Website URL')
                                                ->url()
                                                ->prefix('https://')
                                                ->suffixIcon('heroicon-m-globe-americas'),
                                            Section::make('Principle Contact Information')
                                                ->schema([
                                                    Grid::make(2)->schema([
                                                        TextInput::make('principle_contact_name')
                                                            ->label('Principle Contact Name')
                                                            ->required(),
                                                        TextInput::make('principle_contact_number')
                                                            ->label('Principle Contact Number')
                                                            ->required(),
                                                    ]),
                                                ]),
                                        ]),

                                    Tab::make('Representative Info')
                                        ->icon('heroicon-m-identification')
                                        ->schema([
                                            TextInput::make('representative_name')
                                                ->label('Representative Name')
                                                ->required(),
                                            Grid::make(2)->schema([
                                                TextInput::make('representative_position')
                                                    ->label('Representative Position')
                                                    ->required()
                                                    ->placeholder('e.g., Admissions Officer, Principal, etc.'),
                                                TextInput::make('representative_phone')
                                                    ->label('Representative Phone Number')
                                                    ->required()
                                                    ->tel(),
                                            ]),
                                        ]),
                                ]),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save Profile')
                                ->button()
                                ->color('primary')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ])
                    ]),
            ])
            ->statePath('data')
            ->operation('edit');
    }
    public function save(): void
    {
        $data = $this->form->getState();
        $record = $this->getRecord() ?? new SchoolProfile(['user_id' => auth()->id()]);

        $record->fill($data)->save();
        $this->dispatch('profileUpdated');

        Notification::make()
            ->success()
            ->title('Profile updated successfully.')
            ->send();
    }

    public function getRecord(): ?SchoolProfile
    {
        return SchoolProfile::where('user_id', auth()->id())->first();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Livewire\ProfileCompleteness::class,
        ];
    }
}
