<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\BookCategory;

class BookCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_view_is_accessible()
    {
        $response = $this->get('/book-category/index');

        $response->assertStatus(200);
        $response->assertViewIs('book-category.index');
    }

    public function test_list_returns_all_categories_as_json()
    {
        BookCategory::factory()->count(3)->create();

        $response = $this->getJson('/book-category/list');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'code', 'description', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_store_book_category()
    {
        $data = [
            'name' => 'Mathematics',
            'code' => 'MATH',
            'description' => 'Math related books',
        ];

        $response = $this->postJson('/book-category', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Mathematics']);

        $this->assertDatabaseHas('book_categories', ['code' => 'MATH']);
    }

    public function test_store_validation_fails_with_empty_data()
    {
        $response = $this->postJson('/book-category', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'code']);
    }

    public function test_can_show_specific_book_category()
    {
        $category = BookCategory::factory()->create();

        $response = $this->getJson("/book-category/{$category->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $category->id]);
    }

    public function test_show_returns_404_if_not_found()
    {
        $response = $this->getJson('/book-category/999');

        $response->assertStatus(404);
    }

    public function test_can_update_book_category()
    {
        $category = BookCategory::factory()->create([
            'name' => 'History',
            'code' => 'HIS',
        ]);

        $update = [
            'name' => 'Updated History',
            'code' => 'HIS',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/book-category/{$category->id}", $update);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated History']);

        $this->assertDatabaseHas('book_categories', ['name' => 'Updated History']);
    }

    public function test_can_delete_book_category()
    {
        $category = BookCategory::factory()->create();

        $response = $this->deleteJson("/book-category/{$category->id}");

        $response->assertStatus(200)
                 ->assertJson(['status' => 'deleted']);

        $this->assertDatabaseMissing('book_categories', ['id' => $category->id]);
    }
}
