<?php

namespace App\Filament\Owner\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name') // Relasi ke 'category', ambil kolom 'name'
                    ->searchable() // Agar bisa dicari ketik nama
                    ->preload() // Agar list muncul saat diklik (bagus jika datanya tidak ribuan)
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
