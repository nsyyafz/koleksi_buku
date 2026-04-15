<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;

class VendorMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===================================================================
        // 1. VENDOR: WARUNG BU SRI
        // ===================================================================
        
        $userSri = User::create([
            'name' => 'Bu Sri',
            'email' => 'busri@kantin.test',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'vendor_name' => 'Warung Bu Sri',
            'phone' => '081234567890',
            'address' => 'Kampus C Universitas Airlangga',
        ]);

        $vendorSri = Vendor::create([
            'user_id' => $userSri->id,
            'name' => 'Warung Bu Sri',
            'description' => 'Menyediakan nasi goreng, mie goreng, dan menu Indonesia lainnya',
            'phone' => '081234567890',
            'address' => 'Kampus C Universitas Airlangga',
            'is_active' => true,
        ]);

        // Menu Warung Bu Sri
        $menusSri = [
            ['name' => 'Nasi Goreng Spesial', 'price' => 15000, 'category' => 'makanan', 'description' => 'Nasi goreng dengan telur, ayam, dan sayuran'],
            ['name' => 'Nasi Goreng Biasa', 'price' => 12000, 'category' => 'makanan', 'description' => 'Nasi goreng klasik'],
            ['name' => 'Mie Goreng', 'price' => 10000, 'category' => 'makanan', 'description' => 'Mie goreng pedas'],
            ['name' => 'Ayam Geprek', 'price' => 18000, 'category' => 'makanan', 'description' => 'Ayam goreng geprek sambal'],
            ['name' => 'Es Teh Manis', 'price' => 3000, 'category' => 'minuman', 'description' => 'Es teh manis segar'],
            ['name' => 'Es Jeruk', 'price' => 4000, 'category' => 'minuman', 'description' => 'Es jeruk peras'],
            ['name' => 'Air Mineral', 'price' => 2000, 'category' => 'minuman', 'description' => 'Air mineral dingin'],
        ];

        foreach ($menusSri as $menu) {
            Menu::create([
                'vendor_id' => $vendorSri->id,
                'name' => $menu['name'],
                'description' => $menu['description'],
                'price' => $menu['price'],
                'category' => $menu['category'],
                'is_available' => true,
            ]);
        }

        // ===================================================================
        // 2. VENDOR: KANTIN TEKNIK
        // ===================================================================
        
        $userTeknik = User::create([
            'name' => 'Pak Budi',
            'email' => 'pakbudi@kantin.test',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'vendor_name' => 'Kantin Teknik',
            'phone' => '081234567891',
            'address' => 'Fakultas Teknik Universitas Airlangga',
        ]);

        $vendorTeknik = Vendor::create([
            'user_id' => $userTeknik->id,
            'name' => 'Kantin Teknik',
            'description' => 'Kantin di Fakultas Teknik dengan menu nasi campur dan soto',
            'phone' => '081234567891',
            'address' => 'Fakultas Teknik Universitas Airlangga',
            'is_active' => true,
        ]);

        // Menu Kantin Teknik
        $menusTeknik = [
            ['name' => 'Nasi Campur', 'price' => 15000, 'category' => 'makanan', 'description' => 'Nasi dengan lauk campur'],
            ['name' => 'Soto Ayam', 'price' => 12000, 'category' => 'makanan', 'description' => 'Soto ayam kuah bening'],
            ['name' => 'Bakso', 'price' => 10000, 'category' => 'makanan', 'description' => 'Bakso sapi'],
            ['name' => 'Kopi Hitam', 'price' => 5000, 'category' => 'minuman', 'description' => 'Kopi hitam panas'],
            ['name' => 'Teh Tawar', 'price' => 2000, 'category' => 'minuman', 'description' => 'Teh tawar panas/dingin'],
        ];

        foreach ($menusTeknik as $menu) {
            Menu::create([
                'vendor_id' => $vendorTeknik->id,
                'name' => $menu['name'],
                'description' => $menu['description'],
                'price' => $menu['price'],
                'category' => $menu['category'],
                'is_available' => true,
            ]);
        }

        // ===================================================================
        // 3. VENDOR: KEDAI KOPI VOKASI
        // ===================================================================
        
        $userKopi = User::create([
            'name' => 'Mbak Ani',
            'email' => 'mbakani@kantin.test',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'vendor_name' => 'Kedai Kopi Vokasi',
            'phone' => '081234567892',
            'address' => 'Fakultas Vokasi Universitas Airlangga',
        ]);

        $vendorKopi = Vendor::create([
            'user_id' => $userKopi->id,
            'name' => 'Kedai Kopi Vokasi',
            'description' => 'Kedai kopi dan snack untuk mahasiswa Vokasi',
            'phone' => '081234567892',
            'address' => 'Fakultas Vokasi Universitas Airlangga',
            'is_active' => true,
        ]);

        // Menu Kedai Kopi Vokasi
        $menusKopi = [
            ['name' => 'Kopi Susu', 'price' => 15000, 'category' => 'minuman', 'description' => 'Kopi susu creamy'],
            ['name' => 'Kopi Hitam', 'price' => 10000, 'category' => 'minuman', 'description' => 'Kopi hitam original'],
            ['name' => 'Es Kopi', 'price' => 12000, 'category' => 'minuman', 'description' => 'Kopi dingin segar'],
            ['name' => 'Roti Bakar', 'price' => 8000, 'category' => 'snack', 'description' => 'Roti bakar coklat keju'],
            ['name' => 'Pisang Goreng', 'price' => 5000, 'category' => 'snack', 'description' => 'Pisang goreng crispy'],
        ];

        foreach ($menusKopi as $menu) {
            Menu::create([
                'vendor_id' => $vendorKopi->id,
                'name' => $menu['name'],
                'description' => $menu['description'],
                'price' => $menu['price'],
                'category' => $menu['category'],
                'is_available' => true,
            ]);
        }

        $this->command->info('✅ Vendors and Menus seeded successfully!');
        $this->command->info('   - Warung Bu Sri: 7 menu items');
        $this->command->info('   - Kantin Teknik: 5 menu items');
        $this->command->info('   - Kedai Kopi Vokasi: 5 menu items');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('   Email: busri@kantin.test | Password: password');
        $this->command->info('   Email: pakbudi@kantin.test | Password: password');
        $this->command->info('   Email: mbakani@kantin.test | Password: password');
    }
}