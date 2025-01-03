<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegristResource\Pages;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class RegristResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Registration UID';

    protected static ?string $modelLabel = 'Registration UID';

    protected static ?string $pluralModelLabel = 'Registrations UID';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('UID')
                    ->label('UID')
                    ->maxLength(100)
                    ->nullable(),

                TextInput::make('nim')
                    ->label('NIP/NIM')
                    ->maxLength(50)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $account = Account::where('nim', $get('nim'))->first();
                        if ($account) {
                            $set('role', $account->role);
                            $set('nama', $account->nama);
                        } else {
                            $set('role', null);
                            $set('nama', null);
                        }
                    }),

                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'dosen' => 'Dosen',
                        'mahasiswa' => 'Mahasiswa',
                    ])
                    ->required()
                    ->disabled(),

                TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100)
                    ->disabled(),

                Actions::make([
                    Actions\Action::make('search')
                        ->label('Search')
                        ->action(function (Get $get, Set $set) {
                            $account = Account::where('nim', $get('nim'))->first();
                            if ($account) {
                                $set('role', $account->role);
                                $set('nama', $account->nama);
                            } else {
                                Notification::make()
                                    ->title('Account not found')
                                    ->body('No account found with the provided NIP/NIM.')
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Actions\Action::make('register')
                        ->label('Register')
                        ->action(function (Get $get, Set $set) {
                            $uid = $get('UID');
                            $nim = $get('nim');

                            if ($uid && $nim) {
                                // Cek apakah UID sudah terdaftar di akun lain
                                $existingUID = DB::table('accounts')->where('UID', $uid)->exists();

                                if ($existingUID) {
                                    Notification::make()
                                        ->title('Registration failed')
                                        ->body('UID sudah terdaftar')
                                        ->danger()
                                        ->send();
                                } else {
                                    // Update UID jika belum digunakan
                                    DB::table('accounts')
                                        ->where('nim', $nim)
                                        ->update(['UID' => $uid]);

                                    Notification::make()
                                        ->title('Registration successful')
                                        ->body('The UID has been updated for the account.')
                                        ->success()
                                        ->send();

                                    // Clear the form fields
                                    $set('UID', null);
                                    $set('nim', null);
                                    $set('role', null);
                                    $set('nama', null);
                                }
                            } else {
                                Notification::make()
                                    ->title('Registration failed')
                                    ->body('Please ensure both UID and NIP/NIM are provided.')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->disabled(fn(Get $get): bool => !$get('UID') || !$get('nim')),

                    Actions\Action::make('scan_kartu')
                        ->label('Scan Kartu')
                        ->action(function (Set $set) {

                            $uid = Storage::disk('local')->get('uid.txt');

                            if ($uid) {
                                $set('UID', trim($uid));
                                Notification::make()
                                    ->title('Scan Berhasil')
                                    ->body('UID berhasil diambil dari kartu.')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Scan Gagal')
                                    ->body('Gagal membaca UID dari kartu.')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->color('primary')
                ]),
            ])
            ->statePath('data');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreateRegrist::route('/'),
        ];
    }
}
