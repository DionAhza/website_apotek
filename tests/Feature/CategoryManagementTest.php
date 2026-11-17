<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_admin_can_create_update_and_delete_an_empty_category(): void
    {
        $this->post(route('admin.categories.store'), ['name' => 'Vitamin', 'description' => 'Suplemen'])
            ->assertRedirect(route('admin.categories.index'));
        $category = Category::firstOrFail();

        $this->put(route('admin.categories.update', $category), ['name' => 'Vitamin & Suplemen'])
            ->assertRedirect(route('admin.categories.index'));
        $this->assertSame('Vitamin & Suplemen', $category->fresh()->name);

        $this->delete(route('admin.categories.destroy', $category))->assertRedirect(route('admin.categories.index'));
        $this->assertModelMissing($category);
    }

    public function test_category_name_is_required_and_unique(): void
    {
        Category::factory()->create(['name' => 'Flu']);

        $this->post(route('admin.categories.store'), ['name' => ''])->assertSessionHasErrors('name');
        $this->post(route('admin.categories.store'), ['name' => 'Flu'])->assertSessionHasErrors('name');
    }

    public function test_updating_a_category_with_its_own_name_is_allowed(): void
    {
        $category = Category::factory()->create(['name' => 'Flu']);

        $this->put(route('admin.categories.update', $category), ['name' => 'Flu', 'description' => 'Baru'])
            ->assertSessionHasNoErrors();
    }

    public function test_category_in_use_is_not_deleted_and_its_medicines_survive(): void
    {
        $category = Category::factory()->create();
        $medicine = Medicine::factory()->create(['category_id' => $category->id]);

        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect()
            ->assertSessionHas('gagal');

        $this->assertModelExists($category);
        $this->assertModelExists($medicine);
    }
}
