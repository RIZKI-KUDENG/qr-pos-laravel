<?php

namespace App\Filament\Resources\Tenants\Pages;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Tenants\TenantResource;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1. Ambil data user dari form, lalu hapus dari array $data utama
        // supaya tidak error saat insert ke tabel tenants
        $userData = [
            'name' => $data['admin_name'],
            'email' => $data['admin_email'],
            'password' => Hash::make($data['admin_password']),
            'role' => $data['admin_role']
            
        ];

        unset($data['admin_name'], $data['admin_email'], $data['admin_password']);

        $tenant = static::getModel()::create($data);
        $tenant->users()->create($userData);

        return $tenant;
    }
}
