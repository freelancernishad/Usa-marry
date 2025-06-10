<?php

// app/Http/Controllers/UsaMarry/Api/Admin/Plans/FeatureController.php
namespace App\Http\Controllers\UsaMarry\Api\Admin\Plans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;

class FeatureController extends Controller
{
    public function index()
    {
        return response()->json(Feature::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:features',
            'title_template' => 'required|string',
            'unit' => 'nullable|string',
        ]);

        $feature = Feature::create($validated);
        return response()->json($feature, 201);
    }

    public function show($id)
    {
        return response()->json(Feature::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);

        $validated = $request->validate([
            'key' => 'sometimes|string|unique:features,key,' . $feature->id,
            'title_template' => 'sometimes|string',
            'unit' => 'nullable|string',
        ]);

        $feature->update($validated);
        return response()->json($feature);
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();

        return response()->json(['message' => 'Feature deleted']);
    }


   public function templateInputList()
{
    $features = Feature::all();

    $response = $features->map(function ($feature) {
        preg_match_all('/:(\w+)/', $feature->title_template, $matches);
        $placeholders = $matches[1] ?? [];

        $inputs = collect($placeholders)->map(function ($key) use ($feature) {
            return [
                'name' => $key,
                'type' => 'text',
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'placeholder' => "Enter the " . str_replace('_', ' ', $key) . " for " . str_replace('_', ' ', $feature->key),
            ];
        })->toArray();

        return [
            'key' => $feature->key,
            'title_template' => $feature->title_template,
            'inputs' => $inputs
        ];
    });

    return response()->json($response);
}






}
