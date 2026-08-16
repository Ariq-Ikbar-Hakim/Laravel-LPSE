<?php

use App\Models\User;
use App\Models\Paket;
use App\Models\Lampiran;
use App\Models\LogPaket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('PPK can create draft paket', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);

    $response = $this->actingAs($ppk)->post(route('paket.store'), [
        'kode_rup' => 'RUP-12345',
        'nama_paket' => 'Pengadaan Laptop Kantor',
        'pagu' => 150000000.00,
        'tahun_anggaran' => '2026',
        'pp_id' => $pp->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('paket', [
        'kode_rup' => 'RUP-12345',
        'nama_paket' => 'Pengadaan Laptop Kantor',
        'status' => 'draft',
        'ppk_id' => $ppk->id,
        'pp_id' => $pp->id,
    ]);

    // Check log_paket observer
    $paket = Paket::where('kode_rup', 'RUP-12345')->first();
    $this->assertDatabaseHas('log_paket', [
        'paket_id' => $paket->id,
        'aksi' => 'DRAFT',
    ]);
});

test('PPK can upload lampiran and name formatting versioning works', function () {
    Storage::fake('public');
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'status' => 'draft']);

    $file = UploadedFile::fake()->create('spesifikasi_teknis.pdf', 1000); // 1MB

    $response = $this->actingAs($ppk)->post(route('paket.upload-lampiran', $paket), [
        'file_dokumen' => $file,
        'tipe_dokumen' => 'Spesifikasi Teknis',
    ]);

    $response->assertRedirect();

    // Check lampiran exists in DB
    $lampiran = Lampiran::where('paket_id', $paket->id)->first();
    $this->assertNotNull($lampiran);
    $this->assertEquals('spesifikasi_teknisv1.pdf', $lampiran->nama_file);
    $this->assertEquals('pending', $lampiran->status_validasi);

    // Verify versioning name
    // Format: paket_{id}_{timestamp}_rev{versi}.{ekstensi}
    $pattern = '/^lampiran\/' . $paket->id . '\/paket_' . $paket->id . '_\d+_rev1\.pdf$/';
    $this->assertMatchesRegularExpression($pattern, $lampiran->file_path);
    Storage::disk('public')->assertExists($lampiran->file_path);
});

test('PPK cannot submit empty paket', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'status' => 'draft']);

    $response = $this->actingAs($ppk)->post(route('paket.submit', $paket));
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertEquals('draft', $paket->fresh()->status);
});

test('PP cannot access draft paket', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'status' => 'draft']);

    $response = $this->actingAs($pp)->get(route('paket.show', $paket));
    $response->assertStatus(403);
});

test('PP can review document and approve or reject it', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    
    $paket = Paket::factory()->create([
        'ppk_id' => $ppk->id, 
        'pp_id' => $pp->id, 
        'status' => 'dikirim'
    ]);

    $lampiran = Lampiran::create([
        'paket_id' => $paket->id,
        'file_path' => 'lampiran/1/paket_1_123_rev1.pdf',
        'nama_file' => 'kak.pdf',
        'tipe_dokumen' => 'KAK',
        'uploaded_by' => $ppk->id,
        'status_validasi' => 'pending',
    ]);

    // Test disetujui
    $response = $this->actingAs($pp)->post(route('lampiran.review', $lampiran), [
        'status_validasi' => 'disetujui',
    ]);
    $response->assertRedirect();
    $this->assertEquals('disetujui', $lampiran->fresh()->status_validasi);

    // Test revisi without comments (validation error)
    $response = $this->actingAs($pp)->post(route('lampiran.review', $lampiran), [
        'status_validasi' => 'revisi',
    ]);
    $response->assertSessionHasErrors('catatan');

    // Test revisi with comments
    $response = $this->actingAs($pp)->post(route('lampiran.review', $lampiran), [
        'status_validasi' => 'revisi',
        'catatan' => 'Format file KAK salah.',
    ]);
    $response->assertRedirect();
    $this->assertEquals('revisi', $lampiran->fresh()->status_validasi);

    // Assert comment exists
    $this->assertDatabaseHas('document_comments', [
        'paket_id' => $paket->id,
        'lampiran_id' => $lampiran->id,
        'komentar' => 'Format file KAK salah.',
        'role_saat_komentar' => 'PP',
    ]);
});

test('admin viewing detail paket marks dilihat_admin_at receipt', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $admin = User::factory()->create(['jabatan_aktif' => 'admin', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'status' => 'dikirim']);

    $this->assertNull($paket->dilihat_admin_at);

    $response = $this->actingAs($admin)->get(route('paket.show', $paket));
    $response->assertOk();

    $this->assertNotNull($paket->fresh()->dilihat_admin_at);
});

test('PP bypass creates approved APBD Goods Services package', function () {
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);

    $response = $this->actingAs($pp)->post(route('paket-bypass.store'), [
        'kode_rup' => 'RUP-BYPASS',
        'nama_paket' => 'Bypass Manual PP',
        'pagu' => 50000000.00,
    ]);

    $response->assertRedirect();
    
    $paket = Paket::where('kode_rup', 'RUP-BYPASS')->first();
    $this->assertNotNull($paket);
    $this->assertNull($paket->ppk_id);
    $this->assertEquals($pp->id, $paket->pp_id);
    $this->assertEquals('disetujui', $paket->status);
    $this->assertEquals('Manual (Dibuat PP)', $paket->metode);
    $this->assertEquals('APBD', $paket->sumber_dana);
    $this->assertEquals('Barang/Jasa', $paket->jenis);
});
