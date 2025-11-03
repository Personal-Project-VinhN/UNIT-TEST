<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Transaction controller for handling revenue and expense operations
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    /**
     * TransactionController constructor
     *
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Get all transactions
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Implementation for listing transactions
        return response()->json(['message' => 'Transactions list']);
    }

    /**
     * Create new transaction
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'category_id' => 'required|integer|exists:categories,id',
            'type' => 'required|string|in:revenue,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $transaction = $this->transactionService->createTransaction($data);
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get transaction by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // Implementation for showing single transaction
        return response()->json(['message' => 'Transaction details']);
    }

    /**
     * Update transaction
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'account_id' => 'sometimes|integer|exists:accounts,id',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'type' => 'sometimes|string|in:revenue,expense',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'sometimes|date',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $updated = $this->transactionService->updateTransaction($id, $data);
            return response()->json(['success' => $updated]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete transaction
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->transactionService->deleteTransaction($id);
            return response()->json(['success' => $deleted]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get total revenue for date range
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTotalRevenue(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $total = $this->transactionService->getTotalRevenue(
            $request->start_date,
            $request->end_date
        );

        return response()->json(['total_revenue' => $total]);
    }

    /**
     * Get total expense for date range
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTotalExpense(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $total = $this->transactionService->getTotalExpense(
            $request->start_date,
            $request->end_date
        );

        return response()->json(['total_expense' => $total]);
    }

    /**
     * Get profit for date range
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfit(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $profit = $this->transactionService->getProfit(
            $request->start_date,
            $request->end_date
        );

        return response()->json(['profit' => $profit]);
    }
}
