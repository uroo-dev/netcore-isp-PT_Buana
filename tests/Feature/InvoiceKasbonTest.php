<?php

test('can view invoice index and fetch data', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/invoice');
    $response->assertStatus(200);

    $jsonResponse = $this->actingAs($owner)->getJson('/invoice/data');
    $jsonResponse->assertStatus(200);
});

test('can view invoice create page', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/invoice/buat');
    $response->assertStatus(200);
});

test('can view kasbon index and fetch data', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/kasbon');
    $response->assertStatus(200);

    $jsonResponse = $this->actingAs($owner)->getJson('/kasbon/data');
    $jsonResponse->assertStatus(200);
});
