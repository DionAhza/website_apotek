<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_accessible_to_guests(): void
    {
        $medicine = Medicine::factory()->create();

        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
        $this->get(route('catalog.index'))->assertOk()->assertSee($medicine->name);
        $this->get(route('catalog.show', $medicine))->assertOk()->assertSee($medicine->name);
    }

    public function test_public_pages_do_not_show_admin_buttons_to_guests(): void
    {
        Medicine::factory()->create();

        $this->get(route('catalog.index'))
            ->assertDontSee('Tambah Obat')
            ->assertDontSee('Dashboard Admin');
    }

    public function test_search_filters_by_name(): void
    {
        Medicine::factory()->create(['name' => 'Paramex Flu']);
        Medicine::factory()->create(['name' => 'Vitamin C']);

        $this->get(route('catalog.index', ['q' => 'Paramex']))
            ->assertSee('Paramex Flu')->assertDontSee('Vitamin C');
    }

    public function test_filter_by_category(): void
    {
        $flu = Category::factory()->create();
        Medicine::factory()->create(['name' => 'Obat Flu A', 'category_id' => $flu->id]);
        Medicine::factory()->create(['name' => 'Obat Lain B']);

        $this->get(route('catalog.index', ['category' => $flu->id]))
            ->assertSee('Obat Flu A')->assertDontSee('Obat Lain B');
    }

    public function test_sorting_by_price(): void
    {
        Medicine::factory()->create(['name' => 'Murah', 'price' => 1000]);
        Medicine::factory()->create(['name' => 'Mahal', 'price' => 90000]);

        $this->get(route('catalog.index', ['sort' => 'harga_desc']))->assertSeeInOrder(['Mahal', 'Murah']);
        $this->get(route('catalog.index', ['sort' => 'harga_asc']))->assertSeeInOrder(['Murah', 'Mahal']);
    }

    public function test_catalog_is_paginated_and_keeps_query_string(): void
    {
        Medicine::factory()->count(12)->create(['name' => 'Seri Obat']);

        $this->get(route('catalog.index', ['q' => 'Seri']))
            ->assertOk()
            ->assertViewHas('medicines', fn ($p) => $p->count() === 9 && $p->total() === 12)
            ->assertSee('q=Seri', false);

        $this->get(route('catalog.index', ['q' => 'Seri', 'page' => 2]))
            ->assertViewHas('medicines', fn ($p) => $p->count() === 3);
    }

    public function test_empty_state_is_shown_when_nothing_matches(): void
    {
        $this->get(route('catalog.index', ['q' => 'tidak-ada']))->assertSee('Obat tidak ditemukan');
    }

    public function test_price_is_formatted_in_rupiah_and_expired_is_flagged(): void
    {
        $medicine = Medicine::factory()->expired()->create(['price' => 12500]);

        $this->assertSame('Rp 12.500', $medicine->formatted_price);
        $this->get(route('catalog.show', $medicine))->assertSee('Rp 12.500')->assertSee('Kedaluwarsa');
    }

    public function test_unknown_medicine_returns_404(): void
    {
        $this->get('/katalog/9999')->assertNotFound();
    }
}
