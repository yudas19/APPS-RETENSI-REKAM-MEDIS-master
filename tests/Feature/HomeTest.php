<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Berkas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/home');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_home_page(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Create some sample records with different statuses
        Berkas::create(['no_rm' => 'RM001', 'nama_pasien' => 'Pasien A', 'status' => 'Aktif', 'created_by' => $user->id]);
        Berkas::create(['no_rm' => 'RM002', 'nama_pasien' => 'Pasien B', 'status' => 'Aktif', 'created_by' => $user->id]);
        Berkas::create(['no_rm' => 'RM003', 'nama_pasien' => 'Pasien C', 'status' => 'Inaktif', 'created_by' => $user->id]);
        Berkas::create(['no_rm' => 'RM004', 'nama_pasien' => 'Pasien D', 'status' => 'Musnah', 'created_by' => $user->id]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Beranda Utama');
        $response->assertSee('Berkas Aktif');
        $response->assertSee('Berkas Inaktif');
        $response->assertSee('Berkas Dimusnahkan');
        
        // Assert statistics are displayed correctly
        $response->assertSee('2'); // Active
        $response->assertSee('1'); // Inactive and Musnah
        $response->assertSee('4'); // Total
    }
}
