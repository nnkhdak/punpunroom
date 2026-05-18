<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function index(Request $request, ?string $code_type = null): JsonResponse
    {
        $query = Code::query()->orderBy('code_type')->orderBy('sort_order');

        $codeType = $code_type ?? $request->input('code_type');
        if ($codeType !== null) {
            $query->where('code_type', $codeType);
        }

        return response()->json($query->pluck('name', 'code'));
    }

    public function show(string $codeType, int $code): JsonResponse
    {
        return response()->json(
            Code::where('code_type', $codeType)->where('code', $code)->firstOrFail()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(Code::validationRules());

        if (Code::where('code_type', $validated['code_type'])->where('code', $validated['code'])->exists()) {
            return response()->json(['errors' => ['code' => ['この code_type と code の組み合わせは既に存在します']]], 422);
        }

        $code = Code::create($validated);

        return response()->json($code, 201);
    }

    public function update(Request $request, string $codeType, int $code): JsonResponse
    {
        $record = Code::where('code_type', $codeType)->where('code', $code)->firstOrFail();

        $validated = $request->validate(Code::validationRules($codeType));

        Code::where('code_type', $codeType)->where('code', $code)->update($validated);

        return response()->json(
            Code::where('code_type', $codeType)->where('code', $code)->first()
        );
    }

    public function destroy(string $codeType, int $code): JsonResponse
    {
        Code::where('code_type', $codeType)->where('code', $code)->firstOrFail();

        Code::where('code_type', $codeType)->where('code', $code)->delete();

        return response()->json(null, 204);
    }
}
