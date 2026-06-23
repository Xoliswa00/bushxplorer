<?php

namespace App\Livewire\Auth;

use App\Models\ExplorerLevel;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Register extends Component
{
    public string $firstName            = '';
    public string $lastName             = '';
    public string $email                = '';
    public string $phone                = '';
    public string $password             = '';
    public string $passwordConfirmation = '';

    public function register(): void
    {
        $this->validate([
            'firstName'            => ['required', 'string', 'max:80'],
            'lastName'             => ['required', 'string', 'max:80'],
            'email'                => ['required', 'email', 'unique:users,email'],
            'phone'                => ['nullable', 'string', 'max:20'],
            'password'             => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required'],
        ]);

        DB::transaction(function () {
            $user = User::create([
                'name'     => "{$this->firstName} {$this->lastName}",
                'email'    => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $startingLevel = ExplorerLevel::orderBy('min_points')->first();

            Member::create([
                'user_id'           => $user->id,
                'first_name'        => $this->firstName,
                'last_name'         => $this->lastName,
                'phone'             => $this->phone ?: null,
                'explorer_level_id' => $startingLevel?->id,
                'total_points'      => 0,
                'hikes_attended'    => 0,
                'is_active'         => true,
            ]);

            Auth::login($user, true);
        });

        session()->regenerate();
        $this->redirect(route('member.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
