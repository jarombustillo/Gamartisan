<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Starter accounts, copied from database/gamartisan.sql.
 *
 * Safe to run more than once (php artisan db:seed --force): rows are matched
 * on their ID and updated instead of duplicated.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin')->updateOrInsert(
            ['adminID' => 1],
            ['adminUser' => 'admin', 'adminPass' => '$2y$10$hIHFvRq0P4EMJ3Xz10G5merSbP1qtoj41dk2WqnWHkym70qt5h0ly'],
        );

        $artists = [
            [1, 'Kyle', 'Bustillo', 'kyle@gmail.com', '$2y$10$1XgIC6CDC4vfEdB2SZ2iouxQn3vQg2eIh.R0QGlCShfNqOMm55Ax2', 'I paint an art as a passion.'],
            [2, 'Test', 'Seller', 'testseller@email.com', '$2y$10$DyXALjoc0Ia1EN7yR9QGrexKfan3SxtUMphGUFfzhx5L/IIs70gPO', 'I am creative.'],
        ];
        foreach ($artists as [$id, $first, $last, $email, $pass, $bio]) {
            DB::table('artists')->updateOrInsert(['artistID' => $id], [
                'firstName' => $first, 'midName' => null, 'lastName' => $last,
                'email' => $email, 'artPass' => $pass, 'artBio' => $bio, 'status' => 'active',
            ]);
        }

        $buyers = [
            [1, 'Jarom', 'Bustillo', 'jarombustillo222@gmail.com', '$2y$10$yKlvYnJRudMpO/xxQASoz.81/oMaSDfR7nzdSqZaiczzKnY.psfP6'],
            [2, 'Test', 'Buyer', 'testbuyer@email.com', '$2y$10$Etvi7arfcj5qYwZW.k5e0eBEfR4Gz5aFuYIqQcN7Ov3tmWd35lHGi'],
        ];
        foreach ($buyers as [$id, $first, $last, $email, $pass]) {
            DB::table('buyers')->updateOrInsert(['buyerID' => $id], [
                'firstName' => $first, 'midName' => null, 'lastName' => $last,
                'email' => $email, 'password' => $pass, 'status' => 'active',
            ]);
        }
    }
}
