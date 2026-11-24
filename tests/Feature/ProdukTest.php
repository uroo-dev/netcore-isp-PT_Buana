<?php

use App\Models\Produk;

test('can view produk index', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/produk');
    $response->assertStatus(200);
});

test('can fetch produk data via JSON', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->getJson('/produk/data');
    $response->assertStatus(200);
});

test('can store new product', function () {
    $owner = createOwner();

    $payload = [
        'nama' => 'Router Test AC1200',
        'harga' => 350000,
        'harga_beli' => 280000,
        'stok' => 15,
        'kategori' => 'Router',
    ];

    $response = $this->actingAs($owner)->from('/produk')->post('/produk', $payload);
    $response->assertRedirect('/produk');

    $this->assertDatabaseHas('produk', [
        'nama' => 'Router Test AC1200',
        'harga' => 350000,
    ]);
});

test('can restok existing product', function () {
    $owner = createOwner();

    $produk = Produk::create([
        'nama' => 'Kabel FO Test',
        'harga' => 150000,
        'harga_beli' => 100000,
        'stok' => 10,
        'kategori' => 'Kabel',
    ]);

    $response = $this->actingAs($owner)->from('/produk')->put("/produk/{$produk->id}/restok", [
        'stok' => 20,
        'total_biaya' => 2000000,
    ]);

    $response->assertRedirect('/produk');
    $produk->refresh();
    expect($produk->stok)->toBe(30);
});
