<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for CategoryService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class CategoryServiceTest extends TestCase
{
    protected CategoryService $categoryService;
    protected $categoryRepository;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock repository
        $this->categoryRepository = Mockery::mock(CategoryRepositoryInterface::class);

        // Create service instance with mocked repository
        $this->categoryService = new CategoryService($this->categoryRepository);
    }

    /**
     * Test getting all categories
     */
    public function test_get_all_categories(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/categories.php';
        
        $categoriesData = array_merge(
            $mockData['multiple_revenue_categories'],
            $mockData['multiple_expense_categories']
        );

        $categories = new Collection(
            array_map(function ($data) {
                $category = new Category();
                $category->fill($data);
                return $category;
            }, $categoriesData)
        );

        $this->categoryRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($categories);

        $result = $this->categoryService->getAllCategories();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(6, $result);
    }

    /**
     * Test getting categories by type
     */
    public function test_get_categories_by_type(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/categories.php';

        $revenueCategories = new Collection(
            array_map(function ($data) {
                $category = new Category();
                $category->fill($data);
                return $category;
            }, $mockData['multiple_revenue_categories'])
        );

        $this->categoryRepository
            ->shouldReceive('findByType')
            ->with('revenue')
            ->once()
            ->andReturn($revenueCategories);

        $result = $this->categoryService->getCategoriesByType('revenue');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        $result->each(function ($category) {
            $this->assertEquals('revenue', $category->type);
        });
    }

    /**
     * Test creating a new category
     */
    public function test_create_category(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/categories.php';
        $categoryData = $mockData['valid_revenue_category'];

        $category = new Category();
        $category->id = 1;
        $category->fill($categoryData);

        $this->categoryRepository
            ->shouldReceive('create')
            ->with($categoryData)
            ->once()
            ->andReturn($category);

        $result = $this->categoryService->createCategory($categoryData);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($categoryData['name'], $result->name);
        $this->assertEquals($categoryData['type'], $result->type);
    }

    /**
     * Test updating a category
     */
    public function test_update_category(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/categories.php';
        $updateData = $mockData['category_for_update'];

        $this->categoryRepository
            ->shouldReceive('update')
            ->with(1, $updateData)
            ->once()
            ->andReturn(true);

        $result = $this->categoryService->updateCategory(1, $updateData);

        $this->assertTrue($result);
    }

    /**
     * Test deleting a category
     */
    public function test_delete_category(): void
    {
        $this->categoryRepository
            ->shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn(true);

        $result = $this->categoryService->deleteCategory(1);

        $this->assertTrue($result);
    }

    /**
     * Test updating category that does not exist
     */
    public function test_update_category_not_found(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/categories.php';
        $updateData = $mockData['category_for_update'];
        $nonExistentId = 999;

        // Mock repository to return false (category not found or update failed)
        $this->categoryRepository
            ->shouldReceive('update')
            ->with($nonExistentId, $updateData)
            ->once()
            ->andReturn(false);

        $result = $this->categoryService->updateCategory($nonExistentId, $updateData);

        $this->assertFalse($result);
    }

    /**
     * Test deleting category that does not exist
     */
    public function test_delete_category_not_found(): void
    {
        $nonExistentId = 999;

        // Mock repository to return false (category not found or delete failed)
        $this->categoryRepository
            ->shouldReceive('delete')
            ->with($nonExistentId)
            ->once()
            ->andReturn(false);

        $result = $this->categoryService->deleteCategory($nonExistentId);

        $this->assertFalse($result);
    }

    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
