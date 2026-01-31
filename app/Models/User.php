<?php

namespace App\Models;

use Filament\Panel;
use App\Models\Profile;
use App\Models\SchoolProfile;
use Filament\Facades\Filament;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\Permission\Traits\HasRoles;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasAvatar, HasPasskeys
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the profiles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Get all of the school_profiles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function school_profiles(): HasMany
    {
        return $this->hasMany(SchoolProfile::class);
    }

    /**
     * Get all of the participants for the SchoolProfile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Determine if the user can access Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'individual') {
            return $this->hasRole('me_user') || $this->hasRole('super_admin');
        } elseif ($panel->getId() === 'school') {
            return $this->hasRole('school_user') || $this->hasRole('super_admin');
        } elseif ($panel->getId() === 'mukhiya') {
            return $this->hasRole('super_admin');
        }
        return true;
    }

    /**
     * Get the URL for the user's avatar.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $panelId = Filament::getCurrentPanel()?->getId();

        return match ($panelId) {
            'individual' => $this->getIndividualAvatarUrl(),
            'school' => $this->getSchoolAvatarUrl(),
            'mukhiya' => $this->getMukhiyaAvatarUrl(), // Logic for the Mukhiya panel
            default => asset('images/default_avatar.png'),
        };
    }

    public function getMukhiyaAvatarUrl(): ?string
    {
        // 1. Try School Profile first
        $schoolPath = $this->school_profiles()->latest()->first()?->image_path;
        if ($schoolPath) {
            return asset('storage/' . $schoolPath);
        }

        // 2. If no school profile, try Individual Profile
        $individualPath = $this->profiles()->latest()->first()?->image_path;
        if ($individualPath) {
            return asset('storage/' . $individualPath);
        }

        // 3. Fallback to default
        return asset('images/default_avatar.png');
    }

    public function getIndividualAvatarUrl(): ?string
    {
        $imagePath = $this->profiles()->latest()->first()?->image_path;

        return $imagePath
            ? asset('storage/' . $imagePath)
            : asset('images/default_avatar.png');
    }

    public function getSchoolAvatarUrl(): ?string
    {
        $imagePath = $this->school_profiles()->latest()->first()?->image_path;

        return $imagePath
            ? asset('storage/' . $imagePath)
            : asset('images/default_avatar.png');
    }

    public function getProfileCompleteness(): int
    {
        $panelId = Filament::getCurrentPanel()?->getId();
        $filledFields = 0;
        $totalFields = 0;

        if ($panelId === 'school') {
            $latestSchoolProfile = $this->school_profiles()->latest()->first();
            $fieldsToCheck = [
                'image_path',
                'phone_number',
                'physical_address',
                'representative_name',
                'representative_position',
                'representative_phone',
                'principle_contact_name',
                'principle_contact_number',
            ];
            $totalFields = count($fieldsToCheck);
            foreach ($fieldsToCheck as $field) {
                if (!empty($latestSchoolProfile->{$field})) {
                    $filledFields++;
                }
            }
            $percentage = $totalFields > 0 ? ($filledFields / $totalFields) * 100 : 0;
        } elseif ($panelId === 'individual') {
            $latestProfile = $this->profiles()->latest()->first();
            $fieldsToCheck = [
                'image_path',
                'date_of_birth',
                'grade_level',
                'gender',
                'phone_number',
                'physical_address',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_phone',
            ];
            $totalFields = count($fieldsToCheck);
            foreach ($fieldsToCheck as $field) {
                if (!empty($latestProfile->{$field})) {
                    $filledFields++;
                }
            }
            $percentage = $totalFields > 0 ? ($filledFields / $totalFields) * 100 : 0;
        }

        return $percentage;
    }
}