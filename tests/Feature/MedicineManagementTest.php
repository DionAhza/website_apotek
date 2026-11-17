<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MedicineManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'name' => 'Paracetamol 500 mg',
            'description' => 'Deskripsi obat.',
            'price' => 7500,
            'expired' => now()->addYear()->toDateString(),
            'category_id' => Category::factory()->create()->id,
        ], $override);
    }

    public function test_admin_can_create_medicine_with_valid_image(): void
    {
        $this->post(route('admin.medicines.store'), $this->payload([
            'medicine_image' => UploadedFile::fake()->image('foto asli.jpg', 300, 300),
        ]))->assertRedirect(route('admin.medicines.index'));

        $medicine = Medicine::firstOrFail();
        $this->assertSame('Paracetamol 500 mg', $medicine->name);
        $this->assertStringStartsWith('medicine-images/', $medicine->medicine_image);
        $this->assertStringNotContainsString('foto asli', $medicine->medicine_image); // nama file dari browser tidak dipercaya
        Storage::disk('public')->assertExists($medicine->medicine_image);
    }

    public function test_create_works_without_image(): void
    {
        $this->post(route('admin.medicines.store'), $this->payload())->assertSessionHasNoErrors();

        $this->assertNull(Medicine::firstOrFail()->medicine_image);
    }

    public function test_validation_rejects_bad_input(): void
    {
        $this->post(route('admin.medicines.store'), [
            'name' => '',
            'price' => -5,
            'expired' => 'bukan-tanggal',
            'category_id' => 999,
        ])->assertSessionHasErrors(['name', 'description', 'price', 'expired', 'category_id']);

        $this->assertDatabaseCount('medicines', 0);
    }

    public function test_invalid_upload_is_rejected(): void
    {
        $this->post(route('admin.medicines.store'), $this->payload([
            'medicine_image' => UploadedFile::fake()->create('virus.php', 10, 'application/x-php'),
        ]))->assertSessionHasErrors('medicine_image');

        $this->post(route('admin.medicines.store'), $this->payload([
            'medicine_image' => UploadedFile::fake()->image('besar.jpg')->size(5000),
        ]))->assertSessionHasErrors('medicine_image');

        $this->assertDatabaseCount('medicines', 0);
    }

    public function test_update_keeps_old_image_when_no_new_file_is_uploaded(): void
    {
        $path = UploadedFile::fake()->image('a.jpg')->store('medicine-images', 'public');
        $medicine = Medicine::factory()->create(['medicine_image' => $path]);

        $this->put(route('admin.medicines.update', $medicine), $this->payload([
            'name' => 'Nama Baru',
            'category_id' => $medicine->category_id,
        ]))->assertRedirect(route('admin.medicines.index'));

        $medicine->refresh();
        $this->assertSame('Nama Baru', $medicine->name);
        $this->assertSame($path, $medicine->medicine_image);
        Storage::disk('public')->assertExists($path);
    }

    public function test_update_replaces_image_and_removes_the_old_file(): void
    {
        $old = UploadedFile::fake()->image('a.jpg')->store('medicine-images', 'public');
        $medicine = Medicine::factory()->create(['medicine_image' => $old]);

        $this->put(route('admin.medicines.update', $medicine), $this->payload([
            'category_id' => $medicine->category_id,
            'medicine_image' => UploadedFile::fake()->image('b.png'),
        ]))->assertSessionHasNoErrors();

        $medicine->refresh();
        $this->assertNotSame($old, $medicine->medicine_image);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($medicine->medicine_image);
    }

    public function test_delete_uses_delete_method_and_removes_stored_image(): void
    {
        $path = UploadedFile::fake()->image('a.jpg')->store('medicine-images', 'public');
        $medicine = Medicine::factory()->create(['medicine_image' => $path]);

        $this->delete(route('admin.medicines.destroy', $medicine))->assertRedirect(route('admin.medicines.index'));

        $this->assertModelMissing($medicine);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_delete_cannot_be_triggered_by_get(): void
    {
        $medicine = Medicine::factory()->create();

        $this->get('/admin/obat/'.$medicine->id)->assertStatus(405);
        $this->get('/obat/delete/'.$medicine->id)->assertNotFound();

        $this->assertModelExists($medicine);
    }

    public function test_legacy_image_in_public_gambars_is_never_deleted_from_storage(): void
    {
        $medicine = Medicine::factory()->create(['medicine_image' => 'panadol.webp']);

        $this->assertStringContainsString('gambars/panadol.webp', $medicine->image_url);
        $this->delete(route('admin.medicines.destroy', $medicine))->assertRedirect();
        $this->assertModelMissing($medicine);
    }

    public function test_admin_index_supports_search_and_filter(): void
    {
        $cat = Category::factory()->create();
        Medicine::factory()->create(['name' => 'Amoksilin Cari', 'category_id' => $cat->id]);
        Medicine::factory()->create(['name' => 'Vitamin Lain']);

        $this->get(route('admin.medicines.index', ['q' => 'Amoksilin']))
            ->assertSee('Amoksilin Cari')->assertDontSee('Vitamin Lain');

        $this->get(route('admin.medicines.index', ['category' => $cat->id]))
            ->assertSee('Amoksilin Cari')->assertDontSee('Vitamin Lain');
    }
}
