<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;

/**
 * Category service for managing transaction categories
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class CategoryService
{
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * CategoryService constructor
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    /**
     * Get categories by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesByType(string $type)
    {
        return $this->categoryRepository->findByType($type);
    }

    /**
     * Get active categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories()
    {
        return $this->categoryRepository->getActiveCategories();
    }

    /**
     * Create new category
     *
     * @param array $data
     * @return \App\Models\Category
     */
    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    /**
     * Update category
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCategory(int $id, array $data): bool
    {
        return $this->categoryRepository->update($id, $data);
    }

    /**
     * Delete category
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
