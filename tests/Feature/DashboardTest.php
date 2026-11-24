<?php

test('owner can access the dashboard', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/dashboard');
    $response->assertStatus(200);
});

test('pegawai can access the dashboard', function () {
    $pegawai = createPegawai();

    $response = $this->actingAs($pegawai)->get('/dashboard');
    $response->assertStatus(200);
});

test('owner can access pajak dashboard and fetch data', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/dashboard/pajak');
    $response->assertStatus(200);

    $jsonResponse = $this->actingAs($owner)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson('/dashboard/pajak/data');

    $jsonResponse->assertStatus(200);
});
