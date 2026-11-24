<?php

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

function createOwner(): User
{
    $user = User::where('username', 'uroo')->first();
    if (! $user) {
        $user = User::create([
            'nama_lengkap' => 'Uroo.dev',
            'username' => 'uroo',
            'password' => bcrypt('uroo1234'),
            'role' => 'owner',
        ]);
    }

    return $user;
}

function createPegawai(): User
{
    $cabang = Cabang::first() ?? Cabang::create([
        'nama' => 'Pusat',
        'alamat' => 'Jakarta',
        'telepon' => '081234567890',
    ]);

    return User::create([
        'nama_lengkap' => 'Pegawai Test',
        'username' => 'pegawai_test_'.uniqid(),
        'password' => bcrypt('password'),
        'role' => 'pegawai',
        'cabang_id' => $cabang->id,
    ]);
}

function createCabangUser(): User
{
    $cabang = Cabang::first() ?? Cabang::create([
        'nama' => 'Cabang Test',
        'alamat' => 'Bandung',
        'telepon' => '081234567891',
    ]);

    return User::create([
        'nama_lengkap' => 'Cabang Test User',
        'username' => 'cabang_test_'.uniqid(),
        'password' => bcrypt('password'),
        'role' => 'cabang',
        'cabang_id' => $cabang->id,
    ]);
}
