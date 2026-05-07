<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class Profile extends EditProfile
{
    protected static ?string $title = 'Profile';

    protected Width|string|null $maxWidth = Width::FiveExtraLarge;

    public function defaultForm(Schema $schema): Schema
    {
        return parent::defaultForm($schema)
            ->inlineLabel(false)
            ->columns(1);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile details')
                    ->description('Update the name and email address used for your admin account.')
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                    ])
                    ->columns(2),
                Section::make('Password')
                    ->description('Leave the password fields empty to keep your current password.')
                    ->schema([
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCurrentPasswordFormComponent()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
