<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Person::with('genderCode')->orderBy('id');

        return response()->json($query->get());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Person::with('genderCode')->findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(Person::validationRules());

        $person = Person::create($validated);

        return response()->json($person, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $person = Person::findOrFail($id);

        $validated = $request->validate(Person::validationRules($person->id));

        $person->update($validated);

        return response()->json($person);
    }

    public function destroy(int $id): JsonResponse
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json(null, 204);
    }

}
