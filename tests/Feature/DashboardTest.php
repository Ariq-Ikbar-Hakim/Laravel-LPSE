<?php

use App\Models\User;
use App\Models\Paket;
use App\Models\AssignmentTransfer;

test('Guest is redirected to login from dashboard', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('Admin dashboard renders correctly with required data structure', function () {
    $admin = User::factory()->create(['jabatan_aktif' => 'admin', 'status_aktif' => 1]);
    $activeUser = User::factory()->create(['status_aktif' => 1]);
    $pendingUser = User::factory()->create(['status_aktif' => 0]);
    $paket = Paket::factory()->create(['ppk_id' => $activeUser->id]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('dashboard');
    $response->assertViewHas('data');
    
    $data = $response->viewData('data');
    $this->assertArrayHasKey('total_users', $data);
    $this->assertArrayHasKey('pending_users', $data);
    $this->assertArrayHasKey('total_paket', $data);
    $this->assertArrayHasKey('total_transfers', $data);
    $this->assertArrayHasKey('pending_users_list', $data);
    $this->assertArrayHasKey('recent_transfers_list', $data);
    $this->assertArrayHasKey('chart_status_stats', $data);
});

test('PPK dashboard renders correctly with recent packages and method stats', function () {
    $ppk = User::factory()->create(['jabatan_aktif' => 'PPK', 'status_aktif' => 1]);
    $paket1 = Paket::factory()->create(['ppk_id' => $ppk->id, 'metode' => 'Tender', 'status' => 'draft']);
    $paket2 = Paket::factory()->create(['ppk_id' => $ppk->id, 'metode' => 'E-Purchasing', 'status' => 'disetujui']);

    $response = $this->actingAs($ppk)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('dashboard');
    $response->assertViewHas('data');

    $data = $response->viewData('data');
    $this->assertArrayHasKey('total_paket', $data);
    $this->assertArrayHasKey('draft_paket', $data);
    $this->assertArrayHasKey('disetujui', $data);
    $this->assertArrayHasKey('recent_paket_list', $data);
    $this->assertArrayHasKey('chart_metode_stats', $data);
    
    $this->assertEquals(2, $data['total_paket']);
    $this->assertEquals(1, $data['draft_paket']);
    $this->assertEquals(1, $data['disetujui']);
    $this->assertCount(2, $data['recent_paket_list']);
});

test('PP dashboard renders correctly with active tasks and type stats', function () {
    $pp = User::factory()->create(['jabatan_aktif' => 'PP', 'status_aktif' => 1]);
    $paket1 = Paket::factory()->create(['pp_id' => $pp->id, 'jenis' => 'Barang', 'status' => 'dikirim']);
    $paket2 = Paket::factory()->create(['pp_id' => $pp->id, 'jenis' => 'Jasa Konsultansi', 'status' => 'kaji_ulang']);

    $response = $this->actingAs($pp)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('dashboard');
    $response->assertViewHas('data');

    $data = $response->viewData('data');
    $this->assertArrayHasKey('total_paket', $data);
    $this->assertArrayHasKey('dikirim_paket', $data);
    $this->assertArrayHasKey('kaji_ulang_paket', $data);
    $this->assertArrayHasKey('dikirim_paket_list', $data);
    $this->assertArrayHasKey('chart_jenis_stats', $data);
    
    $this->assertEquals(2, $data['total_paket']);
    $this->assertEquals(1, $data['dikirim_paket']);
    $this->assertEquals(1, $data['kaji_ulang_paket']);
    $this->assertCount(2, $data['dikirim_paket_list']);
});
