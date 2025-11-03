<?php

namespace App\Repositories\Contracts;

/**
 * Category repository interface
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get categories by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get active categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories(): \Illuminate\Database\Eloquent\Collection;
}
