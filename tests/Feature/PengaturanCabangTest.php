<?php

test('can view cabang index', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/cabang');
    $response->assertStatus(200);
});

test('can view pengaturan profil and company info', function () {
    $owner = createOwner();

    $response = $this->actingAs($owner)->get('/pengaturan/profil');
    $response->assertStatus(200);

    $perusahaanResponse = $this->actingAs($owner)->get('/pengaturan/perusahaan');
    $perusahaanResponse->assertStatus(200);
});

test('owner can access akun index and pengaturan paket', function () {
    $owner = createOwner();

    $akunResponse = $this->actingAs($owner)->get('/akun');
    $akunResponse->assertStatus(200);

    $paketResponse = $this->actingAs($owner)->get('/pengaturan/paket');
    $paketResponse->assertStatus(200);

    $pjResponse = $this->actingAs($owner)->get('/pengaturan/penanggung-jawab');
    $pjResponse->assertStatus(200);
});
