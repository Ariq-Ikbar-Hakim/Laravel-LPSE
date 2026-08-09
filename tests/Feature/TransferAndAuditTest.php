<?php

use App\Models\User;
use App\Models\Paket;
use App\Models\AssignmentTransfer;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;

test('PPK or PP can initiate a transfer request to another user with same role', function () {
    $ppk1 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $ppk2 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk1->id, 'status' => 'draft']);

    $response = $this->actingAs($ppk1)->post(route('paket.transfer.store', $paket), [
        'ke_user_id' => $ppk2->id,
        'alasan' => 'Pindah tugas ke dinas luar kota jangka panjang.',
    ]);

    $response->assertRedirect(route('paket.show', $paket));
    $this->assertDatabaseHas('assignment_transfers', [
        'paket_id' => $paket->id,
        'dari_user_id' => $ppk1->id,
        'ke_user_id' => $ppk2->id,
        'tipe_transfer' => 'PPK',
        'status' => 'menunggu',
        'alasan' => 'Pindah tugas ke dinas luar kota jangka panjang.',
    ]);
});

test('Transfer request is blocked if package status is disetujui or selesai', function () {
    $ppk1 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $ppk2 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    
    // Paket disetujui
    $paketDisetujui = Paket::factory()->create(['ppk_id' => $ppk1->id, 'status' => 'disetujui']);
    $response = $this->actingAs($ppk1)->post(route('paket.transfer.store', $paketDisetujui), [
        'ke_user_id' => $ppk2->id,
        'alasan' => 'Pindah tugas.',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('assignment_transfers', ['paket_id' => $paketDisetujui->id]);

    // Paket selesai
    $paketSelesai = Paket::factory()->create(['ppk_id' => $ppk1->id, 'status' => 'selesai']);
    $response = $this->actingAs($ppk1)->post(route('paket.transfer.store', $paketSelesai), [
        'ke_user_id' => $ppk2->id,
        'alasan' => 'Pindah tugas.',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('assignment_transfers', ['paket_id' => $paketSelesai->id]);
});

test('Transfer request is blocked if there is already a pending transfer request', function () {
    $ppk1 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $ppk2 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk1->id, 'status' => 'draft']);

    // Request pertama
    AssignmentTransfer::create([
        'paket_id' => $paket->id,
        'dari_user_id' => $ppk1->id,
        'ke_user_id' => $ppk2->id,
        'tipe_transfer' => 'PPK',
        'status' => 'menunggu',
        'alasan' => 'Alasan pertama.',
    ]);

    // Request kedua -> Gagal
    $response = $this->actingAs($ppk1)->post(route('paket.transfer.store', $paket), [
        'ke_user_id' => $ppk2->id,
        'alasan' => 'Alasan kedua.',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertEquals(1, AssignmentTransfer::where('paket_id', $paket->id)->count());
});

test('Admin can view transfer list and approve a transfer request', function () {
    $admin = User::factory()->create(['jabatan_aktif' => 'admin', 'status_aktif' => 1]);
    $ppk1 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $ppk2 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk1->id, 'status' => 'draft']);

    $transfer = AssignmentTransfer::create([
        'paket_id' => $paket->id,
        'dari_user_id' => $ppk1->id,
        'ke_user_id' => $ppk2->id,
        'tipe_transfer' => 'PPK',
        'status' => 'menunggu',
        'alasan' => 'Pindah tugas.',
    ]);

    // Admin view transfers list
    $response = $this->actingAs($admin)->get(route('admin.transfers.index'));
    $response->assertStatus(200);

    // Admin approve transfer
    $response = $this->actingAs($admin)->post(route('admin.transfers.approve', $transfer));
    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Cek perubahan data
    $this->assertEquals('disetujui', $transfer->fresh()->status);
    $this->assertEquals($admin->id, $transfer->fresh()->disetujui_oleh);
    $this->assertEquals($ppk2->id, $paket->fresh()->ppk_id); // Kepemilikan paket berpindah!

    // Cek pencatatan di log_paket
    $this->assertDatabaseHas('log_paket', [
        'paket_id' => $paket->id,
        'aksi' => 'MUTASI_TUGAS',
    ]);
});

test('Admin can reject a transfer request with reason notes', function () {
    $admin = User::factory()->create(['jabatan_aktif' => 'admin', 'status_aktif' => 1]);
    $ppk1 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $ppk2 = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create(['ppk_id' => $ppk1->id, 'status' => 'draft']);

    $transfer = AssignmentTransfer::create([
        'paket_id' => $paket->id,
        'dari_user_id' => $ppk1->id,
        'ke_user_id' => $ppk2->id,
        'tipe_transfer' => 'PPK',
        'status' => 'menunggu',
        'alasan' => 'Pindah tugas.',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.transfers.reject', $transfer), [
        'catatan_admin' => 'Ditolak karena pejabat penerima sedang memegang terlalu banyak paket.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Cek perubahan data
    $this->assertEquals('ditolak', $transfer->fresh()->status);
    $this->assertEquals('Ditolak karena pejabat penerima sedang memegang terlalu banyak paket.', $transfer->fresh()->catatan_admin);
    $this->assertEquals($ppk1->id, $paket->fresh()->ppk_id); // Kepemilikan paket tetap!
});

test('Role history is saved automatically on user role update via UserObserver', function () {
    $admin = User::factory()->create(['jabatan_aktif' => 'admin', 'status_aktif' => 1]);
    $user = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);

    // Admin updates user's role
    $this->actingAs($admin)->patch(route('admin.users.update-role', $user), [
        'jabatan_aktif' => 'PP',
    ]);

    // Check automatically inserted into user_role_history table
    $this->assertDatabaseHas('user_role_history', [
        'user_id' => $user->id,
        'jabatan_lama' => 'PPK',
        'jabatan_baru' => 'PP',
        'diubah_oleh' => $admin->id,
    ]);
});

test('Audit trail records CRUD events on User and Paket, and login event', function () {
    // 1. Log CREATE on Paket
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket = Paket::factory()->create([
        'nama_paket' => 'Pengadaan Mobil Dinas',
        'kode_rup' => 'RUP-99881',
        'pagu' => 500000000.00,
        'status' => 'draft',
        'ppk_id' => $ppk->id,
    ]);

    $activity = Activity::all()->last();
    $this->assertNotNull($activity);
    $this->assertEquals(Paket::class, $activity->subject_type);
    
    // 2. Log UPDATE on Paket (snapshot check)
    $paket->update(['nama_paket' => 'Pengadaan Mobil Baru']);
    
    $activity = Activity::all()->last();
    $this->assertEquals(Paket::class, $activity->subject_type);
    $this->assertEquals('Pengadaan Mobil Dinas', $activity->attribute_changes['old']['nama_paket']);
    $this->assertEquals('Pengadaan Mobil Baru', $activity->attribute_changes['attributes']['nama_paket']);

    // 3. Log LOGIN Event
    $this->flushSession();
    event(new Login('web', $ppk, false));

    $activity = Activity::all()->last();
    $this->assertEquals('LOGIN', $activity->description);
    $this->assertEquals($ppk->id, $activity->causer_id);
});
