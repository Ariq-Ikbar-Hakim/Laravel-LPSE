<?php

use App\Models\User;
use App\Models\Paket;
use App\Models\Lampiran;
use App\Models\BeritaAcara;
use App\Models\Signature;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('Berita Acara auto generated when package is approved', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'status' => 'draft']);

    $this->assertDatabaseMissing('berita_acara', ['paket_id' => $paket->id]);

    // Set to disetujui
    $paket->update(['status' => 'disetujui']);

    $this->assertDatabaseHas('berita_acara', [
        'paket_id' => $paket->id,
        'status' => 'draft',
    ]);
});

test('PP can sign first and PPK is blocked before PP signs', function () {
    Storage::fake('public');
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'pp_id' => $pp->id, 'status' => 'draft']);
    $paket->update(['status' => 'disetujui']);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();
    $this->assertNotNull($ba);

    // PPK tries to sign before PP -> Expect Forbidden (403)
    $response = $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $response->assertStatus(403);
    $this->assertFalse($ba->hasSignatureFrom('PPK'));

    // PP signs -> Success
    $this->flushSession();
    $response = $this->actingAs($pp)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $response->assertRedirect();
    $this->assertTrue($ba->fresh()->hasSignatureFrom('PP'));
    $this->assertEquals('tanda_tangan_pertama', $ba->fresh()->status);
});

test('PPK is blocked on bypass package signature if no approved lampirans', function () {
    Storage::fake('public');
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);

    // Paket bypass (ppk_id = null)
    $paket = Paket::factory()->create([
        'ppk_id' => null,
        'pp_id' => $pp->id,
        'status' => 'draft',
    ]);
    $paket->update(['status' => 'disetujui']);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();

    // PP signs first
    $this->actingAs($pp)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $this->assertEquals('tanda_tangan_pertama', $ba->fresh()->status);

    // PPK tries to sign -> Expect Forbidden (403) since no lampiran is approved
    $this->flushSession();
    $response = $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $response->assertStatus(403);

    // Upload and approve a lampiran
    Lampiran::create([
        'paket_id' => $paket->id,
        'file_path' => 'lampiran/1/paket_1_123.pdf',
        'nama_file' => 'spek.pdf',
        'tipe_dokumen' => 'Spesifikasi Teknis',
        'uploaded_by' => $pp->id,
        'status_validasi' => 'disetujui', // Approved!
    ]);

    // PPK signs now -> Success
    $this->flushSession();
    $response = $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $response->assertRedirect();
    $this->assertEquals('selesai', $ba->fresh()->status);
    $this->assertEquals('selesai', $paket->fresh()->status);
});

test('Both signatures complete finalizes BA, calculates SHA256 and saves QR Code', function () {
    Storage::fake('public');
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'pp_id' => $pp->id, 'status' => 'draft']);
    $paket->update(['status' => 'disetujui']);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();

    // 1. PP signs
    $this->actingAs($pp)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);

    // 2. PPK signs
    $this->flushSession();
    $response = $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $response->assertSessionHasNoErrors();

    $ba = $ba->fresh();
    $this->assertEquals('selesai', $ba->status);
    $this->assertEquals('selesai', $paket->fresh()->status);

    // Verify PDF and QR Code generation
    $this->assertNotNull($ba->file_laporan);
    Storage::disk('public')->assertExists($ba->file_laporan);
    
    $signaturePpk = $ba->ppkSignature();
    $this->assertNotNull($signaturePpk->qr_code_path);
    Storage::disk('public')->assertExists($signaturePpk->qr_code_path);

    // Check hash exists in signatures
    $this->assertNotNull($signaturePpk->hash_dokumen);
    $this->assertEquals(64, strlen($signaturePpk->hash_dokumen)); // SHA-256 size
});

test('Rollback status to perlu_revisi deletes signatures and resets BA', function () {
    Storage::fake('public');
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'pp_id' => $pp->id, 'status' => 'draft']);
    $paket->update(['status' => 'disetujui']);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();

    // Both sign
    $this->actingAs($pp)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $this->flushSession();
    $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);

    $this->assertEquals(2, $ba->signatures()->count());

    // Paket updated to perlu_revisi (rollback review)
    $paket->update(['status' => 'perlu_revisi']);

    // Signatures should be deleted, BA status back to draft, file_laporan reset
    $ba = $ba->fresh();
    $this->assertEquals(0, $ba->signatures()->count());
    $this->assertEquals('draft', $ba->status);
    $this->assertNull($ba->file_laporan);
});

test('Public verification details page and PDF upload validator work', function () {
    Storage::fake('public');
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk->id, 'pp_id' => $pp->id, 'status' => 'draft']);
    $paket->update(['status' => 'disetujui']);

    $ba = BeritaAcara::where('paket_id', $paket->id)->first();
    
    // Both sign to finalize BA
    $this->actingAs($pp)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $this->flushSession();
    $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('sig.png')
    ]);
    $ba = $ba->fresh();

    // Verify guest access (public verification page)
    $response = $this->get(route('verify', $ba->verification_hash));
    $response->assertStatus(200);

    // Get the final PDF to test matching upload
    $pdfContent = Storage::disk('public')->get($ba->file_laporan);
    $uploadedFile = UploadedFile::fake()->createWithContent('berita_acara_valid.pdf', $pdfContent);

    // Test upload valid file
    $response = $this->post(route('verify.file', $ba->verification_hash), [
        'uploaded_pdf' => $uploadedFile,
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('is_valid', true);

    // Test upload invalid file
    $invalidFile = UploadedFile::fake()->create('hacked.pdf', 100);
    $response = $this->post(route('verify.file', $ba->verification_hash), [
        'uploaded_pdf' => $invalidFile,
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('is_valid', false);
});
