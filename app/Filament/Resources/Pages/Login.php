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
use Filament\Models\Contracts\HasName;

class Login extends BaseLogin
{
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
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        // Add logging for debugging
        Log::info('Auth attempt', [
            'username' => $data['username'],
            'password_provided' => !empty($data['password']),
        ]);

        // Use Auth::attempt() instead of manual verification
        if (!Auth::attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $account = Auth::user();

        // debug
        Log::info('User authenticated', ['id' => $account->id_akun, 'username' => $account->username, 'name' => $account->nama]);

        $panel = Filament::getCurrentPanel();

        $requiredRole = match ($panel->getId()) {
            'admin' => 'admin',
            'dosen' => 'dosen',
            'mahasiswa' => 'mahasiswa',
            default => null,
        };

        if ($requiredRole && $account->role !== $requiredRole) {
            Auth::logout();
            return $this->redirectToAppropriatePanel($account->role);
        }

        session()->regenerate();

        Log::info('Authentication completed', [
            'user' => Auth::user(),
            'filament_user' => Filament::auth()->user(),
            'session' => session()->all()
        ]);

        return app(LoginResponse::class);
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
            'admin' => '/admin',
            'dosen' => '/dosen',
            'mahasiswa' => '/mahasiswa',
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

    protected function getUserName(Account $user): string
    {
        return $user->getName();
    }
}
