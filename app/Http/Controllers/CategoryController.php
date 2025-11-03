<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Category controller for managing transaction categories
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    /**
     * CategoryController constructor
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get all categories
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return response()->json($categories);
    }

    /**
     * Get categories by type
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getByType(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:revenue,expense',
        ]);

        $categories = $this->categoryService->getCategoriesByType($request->type);
        return response()->json($categories);
    }

    /**
     * Create new category
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:revenue,expense',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $category = $this->categoryService->createCategory($data);
        return response()->json($category, 201);
    }

    /**
     * Update category
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:revenue,expense',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $updated = $this->categoryService->updateCategory($id, $data);
        return response()->json(['success' => $updated]);
    }

    /**
     * Delete category
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->categoryService->deleteCategory($id);
        return response()->json(['success' => $deleted]);
    }
}
