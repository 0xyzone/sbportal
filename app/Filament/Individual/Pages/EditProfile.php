<?php

namespace App\Filament\Individual\Pages;

use BackedEnum;
use App\Models\Profile;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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

class EditProfile extends Page
{
    public ?array $data = [];
    protected string $view = 'filament.individual.pages.edit-profile';
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
                                        ->label('Profile Image')
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
                                        ->label('Account Name')
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
                                        ->label('Email Address')
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
                                    Tabs\Tab::make('Personal Info')
                                        ->icon('heroicon-m-user')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                DatePicker::make('date_of_birth')
                                                    ->native(false)
                                                    ->displayFormat('jS F, Y')
                                                    ->date()
                                                    ->required(),
                                                Select::make('gender')
                                                    ->options([
                                                        'male' => 'Male',
                                                        'female' => 'Female',
                                                        'other' => 'Other',
                                                    ])
                                                    ->required(),
                                                Select::make('grade_level')
                                                    ->options(array_combine(range(1, 12), array_map(fn($i) => "$i" . ($i > 3 ? "th" : ["st", "nd", "rd"][$i - 1]) . " Grade", range(1, 12))))
                                                    ->searchable()
                                                    ->required(),
                                            ]),

                                            Grid::make(2)->schema([
                                                TextInput::make('phone_number')->tel()->required(),
                                                TextInput::make('alternate_phone_number')->tel()->different('phone_number'),
                                            ]),

                                            Textarea::make('physical_address')
                                                ->rows(3)
                                                ->columnSpanFull()
                                                ->required(),
                                        ]),

                                    Tabs\Tab::make('Emergency Contact')
                                        ->icon('heroicon-m-exclamation-triangle')
                                        ->schema([
                                            TextInput::make('emergency_contact_name')
                                                ->required(),
                                            Grid::make(2)->schema([
                                                Select::make('emergency_contact_relationship')
                                                    ->options([
                                                        'parent' => 'Parent',
                                                        'sibling' => 'Sibling',
                                                        'relative' => 'Relative',
                                                        'friend' => 'Friend',
                                                        'other' => 'Other',
                                                    ])
                                                    ->required(),
                                                TextInput::make('emergency_contact_phone')->tel()->required()->different('phone_number')->different('alternate_phone_number'),
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
                                ->color('success')
                                ->submit('save')
                                ->keybindings(['mod+s']),
                        ])
                    ]),
            ])
            ->statePath('data')
            ->operation('edit'); // Explicitly defining the operation
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $record = $this->getRecord() ?? new Profile(['user_id' => auth()->id()]);

        $record->fill($data)->save();
        $this->dispatch('profileUpdated');

        Notification::make()
            ->success()
            ->title('Profile updated successfully.')
            ->send();
    }

    public function getRecord(): ?Profile
    {
        return Profile::where('user_id', auth()->id())->first();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Livewire\ProfileCompleteness::class,
        ];
    }
}