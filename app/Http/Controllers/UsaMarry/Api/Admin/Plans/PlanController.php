<?php

namespace App\Http\Controllers\UsaMarry\Api\Admin\Plans;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    // Fetch all plans (list of plans)
    public function index()
    {
        $plans = Plan::all(); // Get all plans from the database
        return response()->json([
            'plans' => $plans
        ]);
    }

    // Fetch a single plan by ID
    public function show($id)
    {
        $plan = Plan::find($id); // Find plan by ID

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        return response()->json($plan);
    }

    // Create a new plan
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'duration' => 'required|string',
            'original_price' => 'required|numeric',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'features' => 'required|array',
        ]);

        // Create a new plan
        $plan = Plan::create([
            'name' => $request->name,
            'duration' => $request->duration,
            'original_price' => $request->original_price,
            'monthly_price' => $request->monthly_price,
            'discount_percentage' => $request->discount_percentage,
            'features' => $request->features, // Store features as a JSON array
        ]);

        return response()->json([
            'message' => 'Plan created successfully',
            'plan' => $plan
        ], 201);
    }

    // Update an existing plan
    public function update(Request $request, $id)
    {
        $plan = Plan::find($id); // Find plan by ID

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        // Validate the request data
        $request->validate([
            'name' => 'required|string',
            'duration' => 'required|string',
            'original_price' => 'required|numeric',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'features' => 'required|array',
        ]);

        // Update the plan
        $plan->update([
            'name' => $request->name,
            'duration' => $request->duration,
            'original_price' => $request->original_price,
            'discount_percentage' => $request->discount_percentage,
            'monthly_price' => $request->monthly_price,
            'features' => $request->features,
        ]);

        return response()->json([
            'message' => 'Plan updated successfully',
            'plan' => $plan
        ]);
    }

    // Delete a plan
    public function destroy($id)
    {
        $plan = Plan::find($id); // Find plan by ID

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $plan->delete(); // Delete the plan
        return response()->json(['message' => 'Plan deleted successfully']);
    }
}
