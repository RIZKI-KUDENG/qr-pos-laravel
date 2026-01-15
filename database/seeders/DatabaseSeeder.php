<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       $cafe1 = Tenant::create(['name' => 'Kopi Kenangan Cabang 1',
        'slug' => 'kopi-kenangan-cabang-1']);
        $cafe2 = Tenant::create(['name' => 'Kopi Kenangan Cabang 2',
        'slug' => 'kopi-kenangan-cabang-2']);

    // 1. Super Admin (Anda)
    User::factory()->create([
        'name' => 'Super Admin Rizki',
        'email' => 'rizki@example.com',
        'role' => 'super_admin', // Role Super Admin
        'tenant_id' => null,     // Tidak terikat toko manapun
    ]);

    // 2. Admin Toko (Owner Cafe)
    User::factory()->create([
        'name' => 'Owner Cafe 1',
        'email' => 'owner@cafe.com',
        'role' => 'admin',       // Role Admin Toko
        'tenant_id' => $cafe1->id,
    ]);
     User::factory()->create([
        'name' => 'Owner Cafe 2',
        'email' => 'owner2@cafe.com',
        'role' => 'admin',       // Role Admin Toko
        'tenant_id' => $cafe2->id,
    ]);
    
    // 3. Kasir Toko (Opsional)
    User::factory()->create([
        'name' => 'Kasir Cafe 1',
        'email' => 'kasir@cafe.com',
        'role' => 'kasir',       // Role Kasir
        'tenant_id' => $cafe1->id,
    ]);
    }
}
