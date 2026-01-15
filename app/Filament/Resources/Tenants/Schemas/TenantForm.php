<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Resto')
                ->components([
                    TextInput::make('name')
                        ->label('Nama Resto')
                        ->required(),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText("Nama domain"),

                    Toggle::make('status')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText('Jika dimatikan, tenant tidak bisa login'),
                ]),

            Section::make('Admin Resto')
                ->components([
                    TextInput::make('admin_name')
                        ->label('Nama Owner')
                        ->required(),

                    TextInput::make('admin_email')
                        ->label('Email Owner')
                        ->email()
                        ->required(),
                        Select::make('admin_role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'kasir' => 'Kasir',
                            ]),

                    TextInput::make('admin_password')
                        ->label('Password Awal')
                        ->password()
                        ->required()
                        ->minLength(6),
                ])
                ->visible(fn (string $operation) => $operation === 'create') 
                ->columns(2),
        ]);
    }
}
