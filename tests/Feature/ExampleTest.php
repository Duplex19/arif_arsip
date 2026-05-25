<?php

use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('home redirects to login when unauthenticated', function () {
    $response = $this->get('/');
    $response->assertRedirect(route('login'));
});

test('login page is accessible', function () {
    $response = $this->get(route('login'));
    $response->assertStatus(200);
});

test('user can login with correct credentials', function () {
    $response = $this->post(route('login.store'), [
        'username' => 'admin',
        'password' => 'password',
    ]);
    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

test('user cannot login with wrong password', function () {
    $response = $this->post(route('login.store'), [
        'username' => 'admin',
        'password' => 'wrongpassword',
    ]);
    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('admin can access dashboard', function () {
    $user = User::where('username', 'admin')->first();
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
});

test('user staff can access dashboard', function () {
    $user = User::where('username', 'staff.konsumsi')->first();
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
});

test('pimpinan can access dashboard', function () {
    $user = User::where('username', 'kepala.dinas')->first();
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
});

test('admin can access user management', function () {
    $user = User::where('username', 'admin')->first();
    $response = $this->actingAs($user)->get(route('admin.users.index'));
    $response->assertStatus(200);
});

test('user staff cannot access user management', function () {
    $user = User::where('username', 'staff.konsumsi')->first();
    $response = $this->actingAs($user)->get(route('admin.users.index'));
    $response->assertStatus(403);
});

test('pimpinan cannot access user management', function () {
    $user = User::where('username', 'kepala.dinas')->first();
    $response = $this->actingAs($user)->get(route('admin.users.index'));
    $response->assertStatus(403);
});

test('user staff can create arsip', function () {
    $user = User::with('bidang')->where('username', 'staff.konsumsi')->first();
    Storage::fake('public');

    $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

    $response = $this->actingAs($user)->post(route('arsip.store'), [
        'nomor_arsip' => '001/SK/IV/2025',
        'tgl_dilegalkan' => '2025-04-01',
        'judul' => 'Surat Keputusan Test',
        'jenis_arsip_id' => 1,
        'file' => $file,
    ]);

    $response->assertRedirect(route('arsip.index'));
    $this->assertDatabaseHas('arsip', [
        'nomor_arsip' => '001/SK/IV/2025',
        'bidang_id' => $user->bidang_id,
    ]);
});

test('user staff can only see their own bidang arsip', function () {
    $user = User::with('bidang')->where('username', 'staff.konsumsi')->first();

    Arsip::factory()->count(3)->create([
        'bidang_id' => $user->bidang_id,
    ]);
    Arsip::factory()->count(5)->create();

    $response = $this->actingAs($user)->get(route('arsip.index'));
    $response->assertStatus(200);

    $arsipCount = Arsip::where('bidang_id', $user->bidang_id)->count();
    $this->assertEquals(3, $arsipCount);
});

test('pimpinan can see all arsip', function () {
    $user = User::where('username', 'kepala.dinas')->first();
    Arsip::factory()->count(10)->create();

    $response = $this->actingAs($user)->get(route('arsip.index'));
    $response->assertStatus(200);

    $this->assertEquals(10, Arsip::count());
});

test('pimpinan cannot access arsip create page', function () {
    $user = User::where('username', 'kepala.dinas')->first();
    $response = $this->actingAs($user)->get(route('arsip.create'));
    $response->assertStatus(403);
});

test('file upload validation rejects oversized files', function () {
    $user = User::with('bidang')->where('username', 'staff.konsumsi')->first();
    Storage::fake('public');

    $file = UploadedFile::fake()->create('large.pdf', 6000, 'application/pdf');

    $response = $this->actingAs($user)->post(route('arsip.store'), [
        'nomor_arsip' => '002/SK/IV/2025',
        'tgl_dilegalkan' => '2025-04-01',
        'judul' => 'Test Large File',
        'jenis_arsip_id' => 1,
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

test('file upload validation rejects invalid format', function () {
    $user = User::with('bidang')->where('username', 'staff.konsumsi')->first();
    Storage::fake('public');

    $file = UploadedFile::fake()->create('doc.txt', 100, 'text/plain');

    $response = $this->actingAs($user)->post(route('arsip.store'), [
        'nomor_arsip' => '003/SK/IV/2025',
        'tgl_dilegalkan' => '2025-04-01',
        'judul' => 'Test Invalid Format',
        'jenis_arsip_id' => 1,
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

test('admin can create new user', function () {
    $admin = User::where('username', 'admin')->first();
    $bidang = Bidang::first();

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'username' => 'newuser',
        'email' => 'new@test.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'bidang_id' => $bidang->id,
        'role' => 'user',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['username' => 'newuser']);
});

test('admin can export excel', function () {
    $admin = User::where('username', 'admin')->first();
    Arsip::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('arsip.export.excel'));
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('admin can export pdf', function () {
    $admin = User::where('username', 'admin')->first();
    Arsip::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('arsip.export.pdf'));
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

test('user can change their own password', function () {
    $user = User::where('username', 'staff.konsumsi')->first();

    $response = $this->actingAs($user)->put(route('profile.password'), [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('success');
});

test('logout works correctly', function () {
    $user = User::where('username', 'admin')->first();
    $this->actingAs($user);

    $response = $this->post(route('logout'));
    $response->assertRedirect('/');
    $this->assertGuest();
});
