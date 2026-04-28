<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * TEST ACCOUNT CREDENTIALS
 * ========================
 * 
 * All test accounts use the password: "password"
 * 
 * OWNER (Full System Access):
 *   Email: owner@boutique.com | Password: password
 * 
 * DAGUPAN BRANCH:
 *   Admin:    admin.dagupan@boutique.com    | Password: password
 *   Staff:    staff.dagupan@boutique.com    | Password: password
 *   Cashier1: cashier.dagupan@boutique.com  | Password: password
 *   Cashier2: cashier2.dagupan@boutique.com | Password: password
 * 
 * SAN CARLOS BRANCH:
 *   Admin:    admin.sancarlos@boutique.com    | Password: password
 *   Staff:    staff.sancarlos@boutique.com    | Password: password
 *   Cashier1: cashier.sancarlos@boutique.com  | Password: password
 *   Cashier2: cashier2.sancarlos@boutique.com | Password: password
 * 
 * ========================
 */

class RoleBasedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Owner
        User::create([
            'name' => 'Shirley D. Velonza', // real name of owner
            'email' => 'owner@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09123456789',
            'role' => 'owner',
        ]);

        // Admin
        User::create([
            'name' => 'San Carlos Admin',
            'email' => 'admin@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09187654321',
            'role' => 'admin',
        ]);

        // Staff
        User::create([
            'name' => 'Inventory Staff',
            'email' => 'staff@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09198765432',
            'role' => 'staff',
        ]);

        // Cashier
        User::create([
            'name' => 'POS Cashier',
            'email' => 'cashier@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09187654322',
            'role' => 'cashier',
        ]);
    }
}
