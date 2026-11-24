<?php

test('can view kas index and fetch kas data', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/kas');
    $response->assertStatus(200);

    $jsonResponse = $this->actingAs($owner)->getJson('/kas/data');
    $jsonResponse->assertStatus(200);
});

test('can store kas transaction', function () {
    $owner = createOwner();

    $payload = [
        'tanggal' => now()->format('Y-m-d'),
        'tipe' => 'masuk',
        'kategori' => 'manual',
        'keterangan' => 'Kas test masuk',
        'jumlah' => 500000,
    ];

    $response = $this->actingAs($owner)->from('/kas')->post('/kas', $payload);
    $response->assertRedirect('/kas');

    $this->assertDatabaseHas('kas', [
        'keterangan' => 'Kas test masuk',
        'jumlah' => 500000,
    ]);
});

test('can recalculate kas saldo', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->from('/kas')->post('/kas/rekalkulasi');
    $response->assertRedirect('/kas');
});

test('owner can view and fetch biaya operasional data', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/biaya-operasional');
    $response->assertStatus(200);

    $jsonResponse = $this->actingAs($owner)->getJson('/biaya-operasional/data');
    $jsonResponse->assertStatus(200);
});
