<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Account controller for managing financial accounts
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class AccountController extends Controller
{
    protected AccountService $accountService;

    /**
     * AccountController constructor
     *
     * @param AccountService $accountService
     * @return void
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Get all accounts
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $accounts = $this->accountService->getAllAccounts();
        return response()->json($accounts);
    }

    /**
     * Create new account
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'account_type' => 'required|string|in:bank,cash,credit_card',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'is_active' => 'nullable|boolean',
        ]);

        $account = $this->accountService->createAccount($data);
        return response()->json($account, 201);
    }

    /**
     * Update account
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'account_type' => 'sometimes|string|in:bank,cash,credit_card',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'is_active' => 'nullable|boolean',
        ]);

        $updated = $this->accountService->updateAccount($id, $data);
        return response()->json(['success' => $updated]);
    }

    /**
     * Delete account
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->accountService->deleteAccount($id);
        return response()->json(['success' => $deleted]);
    }

    /**
     * Get total balance
     *
     * @return JsonResponse
     */
    public function getTotalBalance(): JsonResponse
    {
        $total = $this->accountService->getTotalBalance();
        return response()->json(['total_balance' => $total]);
    }
}
