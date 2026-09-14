<?php

use App\Models\BeritaAcara;
use App\Models\Paket;
use App\Models\Signature;
use App\Models\User;
use App\Services\PdfService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('PDF failure rolls back final signature and allows retry', function () {
    Storage::fake('public');
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['pp_id' => $pp->id, 'ppk_id' => $ppk->id, 'status' => 'disetujui']);
    $ba = BeritaAcara::create(['paket_id' => $paket->id, 'nomor_ba' => 'BA/FAIL', 'verification_hash' => 'fail-hash', 'status' => 'tanda_tangan_pertama']);
    Signature::create(['berita_acara_id' => $ba->id, 'user_id' => $pp->id, 'role_saat_ttd' => 'PP', 'urutan' => 1, 'signed_at' => now()]);
    $this->mock(PdfService::class)->shouldReceive('generate')->once()->andThrow(new RuntimeException('Renderer unavailable'));
    $this->actingAs($ppk)->post(route('berita-acara.sign', $ba), [
        'signature_image' => UploadedFile::fake()->image('signature.png'),
    ])->assertSessionHas('error');
    expect($ba->fresh()->status)->toBe('tanda_tangan_pertama');
    expect($ba->hasSignatureFrom('PPK'))->toBeFalse();
    expect($paket->fresh()->status)->toBe('disetujui');
    expect(Storage::disk('public')->allFiles())->toBe([]);
});

test('repair refuses to replace a previously published document hash', function () {
    $paket = Paket::factory()->create(['ppk_id' => User::factory()->create()->id]);
    $ba = BeritaAcara::create(['paket_id' => $paket->id, 'nomor_ba' => 'BA/REPAIR', 'verification_hash' => 'repair-hash', 'status' => 'selesai', 'file_laporan' => 'berita-acara/original.pdf']);
    foreach (['PP', 'PPK'] as $index => $role) {
        Signature::create(['berita_acara_id' => $ba->id, 'user_id' => User::factory()->create()->id, 'role_saat_ttd' => $role, 'urutan' => $index + 1, 'signed_at' => now(), 'hash_dokumen' => str_repeat('a', 64)]);
    }
    $this->artisan('ba:repair-pdf', ['id' => $ba->id])->assertFailed();
    expect($ba->fresh()->file_laporan)->toBe('berita-acara/original.pdf');
    expect($ba->signatures()->value('hash_dokumen'))->toBe(str_repeat('a', 64));
});

test('repair generates a missing final PDF without creating new signatures', function () {
    Storage::fake('public');
    $pp = User::factory()->create(['jabatan_aktif' => 'PP']);
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK']);
    $paket = Paket::factory()->create(['pp_id' => $pp->id, 'ppk_id' => $ppk->id, 'status' => 'selesai']);
    $ba = BeritaAcara::create(['paket_id' => $paket->id, 'nomor_ba' => 'BA/RECOVER', 'verification_hash' => 'recover-hash', 'status' => 'selesai']);
    foreach (['PP' => $pp, 'PPK' => $ppk] as $role => $user) {
        Signature::create(['berita_acara_id' => $ba->id, 'user_id' => $user->id, 'role_saat_ttd' => $role, 'urutan' => $role === 'PP' ? 1 : 2, 'signed_at' => now()]);
    }
    $this->artisan('ba:repair-pdf', ['id' => $ba->id])->assertSuccessful();
    $content = Storage::disk('public')->get($ba->fresh()->file_laporan);
    expect($content)->toStartWith('%PDF-');
    expect($ba->signatures()->count())->toBe(2);
    expect($ba->signatures()->value('hash_dokumen'))->toBe(hash('sha256', $content));
});
