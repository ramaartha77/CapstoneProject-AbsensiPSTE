<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class EditProfile extends BaseEditProfile
{
    protected function getProfileFormSchema(): array
    {
        return [
            TextInput::make('nama')
                ->label('Name')
                ->required()
                ->maxLength(100),

            TextInput::make('username')
                ->label('Username')
                ->required()
                ->maxLength(25),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(100),

            FileUpload::make('foto')
                ->label('Profile Photo')
                ->image()
                ->disk('public')
                ->directory('profile-photos')
                ->imageEditor()
                ->circleCropper()
                ->preserveFilenames()
                ->imagePreviewHeight('250')
                ->maxSize(5120) // 5MB in KB
                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                ->visible(true), // Ensure it's visible for all roles

            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getProfileFormSchema());
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // If there's an existing photo and a new one is uploaded, delete the old one
        if (isset($data['foto']) && $record->foto && $data['foto'] !== $record->foto) {
            Storage::disk('public')->delete($record->foto);
        }

        $record->update($data);

        return $record;
    }
}
