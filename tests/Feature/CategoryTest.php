<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Artist;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private function asAdmin(): static
    {
        $admin = Admin::create(['adminUser' => 'admin', 'adminPass' => 'x']);

        return $this->withSession(['user_type' => 'admin', 'user_id' => $admin->adminID, 'admin_id' => $admin->adminID, 'user_name' => 'admin']);
    }

    private function asArtist(): static
    {
        $artist = Artist::create(['firstName' => 'A', 'lastName' => 'A', 'email' => 'a@example.test', 'artPass' => 'x', 'status' => 'active']);

        return $this->withSession(['user_type' => 'artist', 'user_id' => $artist->artistID, 'user_name' => 'A']);
    }

    public function test_admin_category_pages_render_with_no_categories(): void
    {
        $this->asAdmin()->get(route('admin.categories.index'))->assertOk();
        $this->asAdmin()->get(route('admin.categories.create'))->assertOk();
    }

    public function test_admin_can_create_a_category(): void
    {
        $this->asAdmin()->post(route('admin.categories.store'), ['catName' => 'Pottery'])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertSame(['Pottery'], Category::pluck('catName')->all());
    }

    public function test_artist_product_form_renders_with_no_categories(): void
    {
        $this->asArtist()->get(route('artist.products.create'))->assertOk();
    }

    public function test_admin_product_form_renders_with_no_categories(): void
    {
        $this->asAdmin()->get(route('admin.products.create'))->assertOk();
    }

    public function test_seeder_adds_starter_categories_to_an_empty_table(): void
    {
        $this->seed();

        $this->assertGreaterThan(0, Category::count());
        $this->assertContains('Pottery & Ceramics', Category::pluck('catName')->all());
    }

    public function test_seeder_never_overrides_categories_an_admin_already_manages(): void
    {
        // db:seed runs on every deploy: renamed/deleted categories must stay that way.
        Category::create(['catName' => 'Only This One']);

        $this->seed();
        $this->seed();

        $this->assertSame(['Only This One'], Category::pluck('catName')->all());
    }

    public function test_artist_is_told_why_a_product_cannot_be_added_without_categories(): void
    {
        $this->asArtist()->get(route('artist.products.create'))
            ->assertOk()
            ->assertSee('No categories yet');
    }

    public function test_admin_product_form_links_to_category_creation_when_empty(): void
    {
        $this->asAdmin()->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee(route('admin.categories.create'), false);
    }
}
