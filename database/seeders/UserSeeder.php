<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seedUser = function (array $data): void {
            $email = $data['email'];

            /** @var User|null $existing */
            $existing = User::withTrashed()->where('email', $email)->first();

            if ($existing) {
                if (method_exists($existing, 'restore') && $existing->trashed()) {
                    $existing->restore();
                }

                // Do not reset passwords on every deploy.
                unset($data['password']);

                $existing->fill($data);
                $existing->save();
                return;
            }

            // Ensure password is hashed for new seed accounts.
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            User::create($data);
        };

        // Create Owner
        $seedUser([
            'name' => 'Shirley D. Velonza', // real name of owner
            'email' => 'owner@boutique.com',
            'password' => 'password',
            'contact_number' => '09123456789',
            'role' => 'owner',
        ]);

        // Admin
        $seedUser([
            'name' => 'San Carlos Admin',
            'email' => 'admin@boutique.com',
            'password' => 'password',
            'contact_number' => '09187654321',
            'role' => 'admin',
        ]);

        // Staff
        $seedUser([
            'name' => 'Inventory Staff',
            'email' => 'staff@boutique.com',
            'password' => 'password',
            'contact_number' => '09198765432',
            'role' => 'staff',
        ]);

        // Cashier
        $seedUser([
            'name' => 'POS Cashier',
            'email' => 'cashier@boutique.com',
            'password' => 'password',
            'contact_number' => '09187654322',
            'role' => 'cashier',
        ]);
    }
}
