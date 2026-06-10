<?php

namespace App\Http\Controllers\Api\V1\Expense;

use App\Http\Controllers\Controller;
use Domain\Expense\Actions\CreateExpenseAction;
use Domain\SharedKernel\Exceptions\DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Domain\Expense\Models\Expense;

class ExpenseController extends Controller
{
    public function __construct(
        private CreateExpenseAction $createExpenseAction
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'base_currency_amount' => 'nullable|numeric',
            'exchange_rate' => 'nullable|numeric',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_id'] = $request->user()->id;
            $data['tenant_id'] = $request->user()->tenant_id;

            $expense = $this->createExpenseAction->execute($data);

            return response()->json([
                'message' => 'Expense recorded successfully',
                'data' => tap($expense, function ($e) {
                    // Decrypt for response output implicitly if using toArray, but we just return object
                    $e->makeVisible(['title', 'amount']);
                })
            ], 201);
            
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $expenses = Expense::with('category')
            ->where('user_id', $request->user()->id)
            ->orderBy('expense_date', 'desc')
            ->paginate(15);
            
        // Because fields are encrypted, Laravel handles decryption automatically via the cast when accessing the attributes.
        return response()->json($expenses);
    }
}
