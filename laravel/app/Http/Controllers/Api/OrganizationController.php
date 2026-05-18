<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Organization::with('representative')->orderBy('id');

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%')
                  ->orWhere('name_kana', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        return response()->json($query->get());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Organization::with('representative')->findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(Organization::validationRules());

        return response()->json(Organization::create($validated), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $organization = Organization::findOrFail($id);
        $organization->update($request->validate(Organization::validationRules($organization->id)));

        return response()->json($organization);
    }

    public function destroy(int $id): JsonResponse
    {
        Organization::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
