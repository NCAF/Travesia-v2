<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC005: Verifikasi Sign Up berhasil dengan input valid
     */
    public function testSuccessfulUserRegistration()
    {
        // Kunjungi halaman register
        $response = $this->get('/register');
        

        // Lakukan POST untuk registrasi
        $response = $this->from('/register')->post('/register', [
            'nama' => 'Serli Aprilia luecene', // pastikan field ini sesuai dengan kolom di tabel users
            'email' => 'ser@gmail.com',
            'password' => 'Serli123456',
            'password_confirmation' => 'Serli123456',
        ]);

        // Pastikan user tersimpan di database
        $this->assertDatabaseHas('users', [
            'email' => 'ser@gmail.com',
            'nama'  => 'Serli Aprilia luecene',
        ]);

        // Pastikan redirect ke login
        $response->assertRedirect('/login');

        // Pastikan session memiliki pesan sukses
        $response->assertSessionHas('success', 'Berhasil melakukan registrasi. Silahkan Login.');
    }

    /**
     * TC006: Verifikasi Sign Up gagal saat input kosong
     */
    public function testSignUpFailsWithEmptyFields()
    {
        // Kunjungi halaman register
        $response = $this->get('/register');
        $response->assertStatus(200);

        // Kirim form kosong
        $response = $this->from('/register')->post('/register', [
            'nama' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        // Pastikan validasi gagal
        $response->assertSessionHasErrors(['nama', 'email', 'password']);

        // Pastikan redirect kembali ke halaman register
        $response->assertRedirect('/register');
    }
}