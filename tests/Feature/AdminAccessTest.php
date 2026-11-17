<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public static function adminUrls(): array
    {
        return [
            'dashboard' => ['/admin'],
            'daftar obat' => ['/admin/obat'],
            'tambah obat' => ['/admin/obat/create'],
            'daftar kategori' => ['/admin/kategori'],
            'tambah kategori' => ['/admin/kategori/create'],
        ];
    }

    #[DataProvider('adminUrls')]
    public function test_guest_is_redirected_to_login(string $url): void
    {
        $this->get($url)->assertRedirect(route('login'));
    }

    #[DataProvider('adminUrls')]
    public function test_regular_user_gets_403(string $url): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))->get($url)->assertForbidden();
    }

    #[DataProvider('adminUrls')]
    public function test_admin_can_open_admin_pages(string $url): void
    {
        $this->actingAs($this->admin())->get($url)->assertOk();
    }

    public function test_regular_user_cannot_write_through_admin_routes(): void
    {
        $medicine = Medicine::factory()->create();
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->delete(route('admin.medicines.destroy', $medicine))->assertForbidden();
        $this->actingAs($user)->post(route('admin.categories.store'), ['name' => 'X'])->assertForbidden();

        $this->assertModelExists($medicine);
    }

    public function test_dashboard_statistics_come_from_the_database(): void
    {
        Medicine::factory()->count(3)->create();
        Medicine::factory()->expiringSoon()->create(['name' => 'Obat Hampir Exp']);
        Medicine::factory()->expired()->create();

        $this->actingAs($this->admin())->get('/admin')
            ->assertOk()
            ->assertViewHas('medicineCount', 5)
            ->assertViewHas('expiringSoonCount', 1)
            ->assertViewHas('expiredCount', 1)
            ->assertSee('Obat Hampir Exp');
    }

    public function test_old_urls_still_redirect(): void
    {
        $medicine = Medicine::factory()->create();

        $this->get('/obat')->assertRedirect('/admin/obat');
        $this->get('/category')->assertRedirect('/admin/kategori');
        $this->get('/obat/'.$medicine->id)->assertRedirect(route('catalog.show', $medicine));
    }
}
