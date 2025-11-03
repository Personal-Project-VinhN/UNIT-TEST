<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

/**
 * Category repository implementation
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor
     */
    public function __construct()
    {
        parent::__construct(new Category());
    }

    /**
     * Get categories by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('type', $type)->get();
    }

    /**
     * Get active categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_active', true)->get();
    }
}
