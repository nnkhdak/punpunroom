<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CodeController extends Controller
{
    /**
     * 一覧取得
     * GET /api/codes
     * GET /api/codes?type=gender
     */
    public function index(Request $request): JsonResponse
    {
        $query = Code::query()->orderBy('code_type')->orderBy('sort_order');

        if ($request->filled('type')) {
            $query->where('code_type', $request->input('type'));
        }

        return response()->json($query->get());
    }

    /**
     * 1件取得
     * GET /api/codes/{codeType}/{code}
     */
    public function show(string $codeType, int $code): JsonResponse
    {
        $item = Code::where('code_type', $codeType)->where('code', $code)->first();

        if ($item === null) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        return response()->json($item);
    }

    /**
     * 作成
     * POST /api/codes
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code_type'  => ['required', 'string', 'max:50'],
            'code'       => ['required', 'integer', 'between:0,255'],
            'name'       => ['required', 'string', 'max:50'],
            'sort_order' => ['sometimes', 'integer', 'between:0,255'],
        ]);

        $exists = Code::where('code_type', $validated['code_type'])
            ->where('code', $validated['code'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already exists.'], 409);
        }

        Code::create($validated);

        $item = Code::where('code_type', $validated['code_type'])
            ->where('code', $validated['code'])
            ->first();

        return response()->json($item, 201);
    }

    /**
     * 更新
     * PUT /api/codes/{codeType}/{code}
     */
    public function update(Request $request, string $codeType, int $code): JsonResponse
    {
        $exists = Code::where('code_type', $codeType)->where('code', $code)->exists();

        if (!$exists) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $validated = $request->validate([
            'name'       => ['sometimes', 'string', 'max:50'],
            'sort_order' => ['sometimes', 'integer', 'between:0,255'],
        ]);

        Code::where('code_type', $codeType)
            ->where('code', $code)
            ->update($validated);

        $item = Code::where('code_type', $codeType)->where('code', $code)->first();

        return response()->json($item);
    }

    /**
     * 削除
     * DELETE /api/codes/{codeType}/{code}
     */
    public function destroy(string $codeType, int $code): JsonResponse
    {
        $deleted = Code::where('code_type', $codeType)->where('code', $code)->delete();

        if ($deleted === 0) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        return response()->json(null, 204);
    }
}
