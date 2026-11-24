<?php

test('can view laporan index and fetch laporan data', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/laporan');
    $response->assertStatus(200);

    $jsonResponse = $this->actingAs($owner)->getJson('/laporan/data');
    $jsonResponse->assertStatus(200);
});

test('can view absensi index and form', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/absensi');
    $response->assertStatus(200);

    $formResponse = $this->actingAs($owner)->get('/absensi/form');
    $formResponse->assertStatus(200);
});

test('can view slip gaji index and create page', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/slip-gaji');
    $response->assertStatus(200);

    $createResponse = $this->actingAs($owner)->get('/slip-gaji/create');
    $createResponse->assertStatus(200);
});
