<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Filament\Widgets\Widget;

class ProfileCompleteness extends Widget
{
    protected string $view = 'livewire.profile-completeness';

    #[On('profileUpdated')] 
    public function refreshWidget(): void
    {
        // This method being called is enough to trigger a re-render
        // of the view, fetching the fresh percentage from the User model.
    }
}
