<?php

namespace App\Http\Controllers\Api\V1\Income;

use App\Http\Controllers\Controller;
use Domain\Income\Actions\RecordIncomeEntryAction;
use Domain\SharedKernel\Exceptions\DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Domain\Income\Models\IncomeEntry;

class IncomeController extends Controller
{
    public function __construct(
        private RecordIncomeEntryAction $recordIncomeAction
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'source_type_id' => 'required|string|exists:income_source_types,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'base_currency_amount' => 'nullable|numeric',
            'exchange_rate' => 'nullable|numeric',
            'income_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_id'] = $request->user()->id;
            $data['tenant_id'] = $request->user()->tenant_id;

            $income = $this->recordIncomeAction->execute($data);

            return response()->json([
                'message' => 'Income recorded successfully',
                'data' => $income
            ], 201);
            
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $incomes = IncomeEntry::with('sourceType')
            ->where('user_id', $request->user()->id)
            ->orderBy('income_date', 'desc')
            ->paginate(15);
            
        return response()->json($incomes);
    }
}
