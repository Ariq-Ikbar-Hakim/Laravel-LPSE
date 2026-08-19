<?php

use App\Models\User;
use App\Models\Paket;
use App\Models\BeritaAcara;
use App\Models\Signature;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('PP can create manual Berita Acara and PPK can sign it', function () {
    Storage::fake('public');
    
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);

    // 1. PP membuat Berita Acara secara manual (Offline)
    $response = $this->actingAs($pp)->post(route('berita-acara.store-manual'), [
        'ppk_id' => $ppk->id,
        'nama_paket' => 'Pengadaan Laptop Manual',
        'kode_rup' => 'RUP-MANUAL-123',
        'tahun_anggaran' => '2026',
        'pagu' => 200000000,
        'hps' => 195000000,
        'nomor_ba' => 'BA/MANUAL/1/2026',
        'tanggal_ba' => '2026-08-16',
        'signature_image' => UploadedFile::fake()->image('signature_pp.png'),
    ]);

    $response->assertRedirect(route('berita-acara.index'));
    $this->assertDatabaseHas('paket', [
        'nama_paket' => 'Pengadaan Laptop Manual',
        'kode_rup' => 'RUP-MANUAL-123',
        'hps' => 195000000,
        'status' => 'disetujui'
    ]);

    $paket = Paket::where('kode_rup', 'RUP-MANUAL-123')->first();
    $this->assertDatabaseHas('berita_acara', [
        'paket_id' => $paket->id,
        'nomor_ba' => 'BA/MANUAL/1/2026',
        'status' => 'tanda_tangan_pertama'
    ]);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();
    $this->assertDatabaseHas('signatures', [
        'berita_acara_id' => $ba->id,
        'user_id' => $pp->id,
        'role_saat_ttd' => 'PP',
        'urutan' => 1
    ]);

    // 2. PPK menandatangani Berita Acara tersebut
    $this->flushSession();
    $response = $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('signature_ppk.png')
    ]);

    $response->assertRedirect();
    $this->assertEquals('selesai', $ba->fresh()->status);
    $this->assertEquals('selesai', $paket->fresh()->status);
    
    // Verifikasi PDF final terbentuk
    $this->assertNotNull($ba->fresh()->file_laporan);
    Storage::disk('public')->assertExists($ba->fresh()->file_laporan);
});

test('PP can link Berita Acara to an existing package and PPK can sign it', function () {
    Storage::fake('public');
    
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);

    // Buat paket terdaftar di sistem
    $paket = Paket::factory()->create([
        'ppk_id' => $ppk->id,
        'pp_id' => $pp->id,
        'nama_paket' => 'Paket Laptop Terdaftar',
        'kode_rup' => 'RUP-SISTEM-999',
        'pagu' => 120000000,
        'hps' => null,
        'status' => 'dikirim'
    ]);

    // 1. PP menghubungkan Berita Acara ke paket tersebut
    $response = $this->actingAs($pp)->post(route('berita-acara.store-manual'), [
        'paket_id' => $paket->id,
        'ppk_id' => $ppk->id,
        'nama_paket' => 'Paket Laptop Terdaftar', // detail disinkronkan
        'kode_rup' => 'RUP-SISTEM-999',
        'tahun_anggaran' => '2026',
        'pagu' => 120000000,
        'hps' => 118000000, // HPS baru
        'nomor_ba' => 'BA/LINKED/9/2026',
        'tanggal_ba' => '2026-08-16',
        'signature_image' => UploadedFile::fake()->image('signature_pp.png'),
    ]);

    $response->assertRedirect(route('berita-acara.index'));
    
    $paket = $paket->fresh();
    $this->assertEquals('disetujui', $paket->status);
    $this->assertEquals(118000000, (int) $paket->hps);

    $this->assertDatabaseHas('berita_acara', [
        'paket_id' => $paket->id,
        'nomor_ba' => 'BA/LINKED/9/2026',
        'status' => 'tanda_tangan_pertama'
    ]);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();

    // 2. PPK menandatangani Berita Acara tersebut
    $this->flushSession();
    $response = $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('signature_ppk.png')
    ]);

    $response->assertRedirect();
    $this->assertEquals('selesai', $ba->fresh()->status);
    $this->assertEquals('selesai', $paket->fresh()->status);
    
    // Verifikasi PDF final terbentuk
    $this->assertNotNull($ba->fresh()->file_laporan);
    Storage::disk('public')->assertExists($ba->fresh()->file_laporan);
});
