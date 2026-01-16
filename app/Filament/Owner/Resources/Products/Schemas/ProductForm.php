<?php

namespace App\Filament\Owner\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name') 
                    ->searchable() 
                    ->preload() 
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                      Textarea::make('description')
                    ->label('Deskripsi Produk')
                    ->rows(4)
                    ->columnSpanFull(),
                    FileUpload::make('image')
                    ->label('Foto Produk')
                    ->image()
                    ->directory('products')
                    ->imagePreviewHeight('200')
                    ->maxSize(1024) 
                    ->nullable(),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
