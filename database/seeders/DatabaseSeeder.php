<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ──────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'superadmin@gstportal.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
            ]
        );

        // ── Demo Client + Admin User ─────────────────────────────────────────
        $client = Client::firstOrCreate(
            ['business_name' => 'Demo Business Pvt Ltd'],
            [
                'ntn'              => '1234567-8',
                'strn'             => 'SRB-123456789',
                'province'         => 'SRB',
                'default_gst_rate' => 13,
                'address'          => '123 Main Street, Karachi',
                'city'             => 'Karachi',
                'phone'            => '+92-21-1234567',
                'email'            => 'info@demobusiness.com',
                'invoice_prefix'   => 'DEMO',
                'invoice_counter'  => 1,
                'profile_complete' => true,
                'is_active'        => true,
            ]
        );

        $clientAdmin = User::firstOrCreate(
            ['email' => 'admin@demobusiness.com'],
            [
                'name'      => 'Demo Admin',
                'password'  => Hash::make('password123'),
                'role'      => 'client_admin',
                'client_id' => $client->id,
            ]
        );

        // ── Sample Customers ─────────────────────────────────────────────────
        $customers = [
            [
                'name'      => 'ABC Trading Co.',
                'ntn'       => '9876543-2',
                'province'  => 'SRB',
                'city'      => 'Karachi',
                'address'   => '45 Tariq Road, Karachi',
                'email'     => 'accounts@abctrading.com',
                'phone'     => '+92-300-1234567',
            ],
            [
                'name'      => 'XYZ Corporation',
                'ntn'       => '5551234-1',
                'province'  => 'PRA',
                'city'      => 'Lahore',
                'address'   => '10 Gulberg III, Lahore',
                'email'     => 'billing@xyzcorp.com',
                'phone'     => '+92-321-7654321',
            ],
            [
                'name'      => 'Fast Logistics Ltd',
                'province'  => 'FBR',
                'city'      => 'Islamabad',
                'address'   => 'F-7/2, Islamabad',
            ],
        ];

        foreach ($customers as $c) {
            Customer::firstOrCreate(
                ['client_id' => $client->id, 'name' => $c['name']],
                array_merge($c, ['client_id' => $client->id, 'is_active' => true])
            );
        }

        // ── Sample Products ──────────────────────────────────────────────────
        $products = [
            [
                'name'        => 'Web Development Services',
                'description' => 'Custom website and web application development',
                'hs_code'     => null,
                'price'       => 150000,
                'gst_rate'    => 13,
                'tax_type'    => 'standard',
                'unit'        => 'Service',
            ],
            [
                'name'        => 'Annual Support Package',
                'description' => '12-month technical support and maintenance',
                'hs_code'     => null,
                'price'       => 60000,
                'gst_rate'    => 13,
                'tax_type'    => 'standard',
                'unit'        => 'Year',
            ],
            [
                'name'        => 'Server Hosting (Monthly)',
                'description' => 'VPS cloud hosting per month',
                'hs_code'     => null,
                'price'       => 8000,
                'gst_rate'    => 13,
                'tax_type'    => 'standard',
                'unit'        => 'Month',
            ],
            [
                'name'        => 'Domain Registration',
                'description' => 'Annual domain name registration',
                'hs_code'     => null,
                'price'       => 2500,
                'gst_rate'    => 0,
                'tax_type'    => 'zero_rated',
                'unit'        => 'Year',
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['client_id' => $client->id, 'name' => $p['name']],
                array_merge($p, ['client_id' => $client->id, 'is_active' => true])
            );
        }

        $this->command->info('✅ Database seeded!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('  Super Admin:  superadmin@gstportal.com / password123');
        $this->command->info('  Demo Client:  admin@demobusiness.com   / password123');
    }
}
