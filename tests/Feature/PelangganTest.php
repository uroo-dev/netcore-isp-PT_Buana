<?php

use App\Models\Pelanggan;

test('can view pelanggan index', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/pelanggan');
    $response->assertStatus(200);
});

test('can fetch tab data for individu pelanggan', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/pelanggan/data/individu');
    $response->assertStatus(200);
});

test('can store new pelanggan', function () {
    $owner = createOwner();

    $payload = [
        'nama' => 'Test Pelanggan Dummy',
        'telepon' => '081299998888',
        'alamat' => 'Jl. Pengujian No. 123',
        'tipe' => 'individu',
    ];

    $response = $this->actingAs($owner)->post('/pelanggan', $payload);
    $response->assertRedirect('/pelanggan?tab=individu');

    $this->assertDatabaseHas('pelanggan', [
        'nama' => 'Test Pelanggan Dummy',
        'telepon' => '081299998888',
    ]);
});

test('can show pelanggan detail and update it', function () {
    $owner = createOwner();

    $pelanggan = Pelanggan::create([
        'nama' => 'Pelanggan Asli',
        'telepon' => '081122334455',
        'alamat' => 'Alamat Asli',
        'tipe' => 'individu',
        'status' => 'on',
    ]);

    $showResponse = $this->actingAs($owner)->get("/pelanggan/{$pelanggan->id}");
    $showResponse->assertStatus(200);

    $updateResponse = $this->actingAs($owner)->from("/pelanggan/{$pelanggan->id}")->put("/pelanggan/{$pelanggan->id}", [
        'nama' => 'Pelanggan Terupdate',
        'telepon' => '089988776655',
        'alamat' => 'Alamat Baru',
        'tipe' => 'individu',
    ]);

    $updateResponse->assertRedirect("/pelanggan/{$pelanggan->id}");
    $this->assertDatabaseHas('pelanggan', [
        'id' => $pelanggan->id,
        'nama' => 'Pelanggan Terupdate',
    ]);
});

test('can toggle pelanggan status', function () {
    $owner = createOwner();

    $pelanggan = Pelanggan::create([
        'nama' => 'Pelanggan Toggle',
        'telepon' => '081122334499',
        'alamat' => 'Alamat',
        'tipe' => 'individu',
        'status' => 'on',
    ]);

    $response = $this->actingAs($owner)->from('/pelanggan')->put("/pelanggan/{$pelanggan->id}/toggle-status");
    $response->assertRedirect('/pelanggan');

    $pelanggan->refresh();
    expect($pelanggan->status)->toBe('off');
});
