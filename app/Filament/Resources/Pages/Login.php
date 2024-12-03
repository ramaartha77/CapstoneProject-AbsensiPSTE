<?php

namespace App\Filament\Pages\Auth;

use App\Models\Account;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Log;

class Login extends BaseLogin
{
    protected static ?string $title = 'Sistem Absensi PSTE';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->autocomplete('username'),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (!Auth::attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Auth::user();


        session()->regenerate();


        return $this->redirectToAppropriatePanel($user->role);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function redirectToAppropriatePanel(string $role): LoginResponse
    {
        $redirectUrl = match ($role) {
            'admin' => '/admin', // Changed from /admin/dashboard
            'dosen' => '/dosen', // Changed from /dosen/dashboard
            'mahasiswa' => '/mahasiswa', // Changed from /mahasiswa/dashboard
            default => '/',
        };

        return new class($redirectUrl) implements LoginResponse {
            public function __construct(private string $url) {}

            public function toResponse($request)
            {
                return redirect()->to($this->url);
            }
        };
    }
}
