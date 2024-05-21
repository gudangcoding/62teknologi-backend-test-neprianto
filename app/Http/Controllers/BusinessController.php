<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'alias' => 'required|string',
            'url' => 'required|string',
            'phone' => 'nullable|string',
            'display_phone' => 'nullable|string',
            'rating' => 'required|numeric',
            'review_count' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'categories' => 'required|array',
        ]);

        $data['categories'] = json_encode($data['categories']);

        $business = Business::create($data);
        return response()->json($business, 201);
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'alias' => 'sometimes|string',
            'url' => 'sometimes|string',
            'phone' => 'nullable|string',
            'display_phone' => 'nullable|string',
            'rating' => 'sometimes|numeric',
            'review_count' => 'sometimes|integer',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'zip_code' => 'sometimes|string',
            'country' => 'sometimes|string',
            'state' => 'sometimes|string',
            'categories' => 'sometimes|array',
        ]);

        if (isset($data['categories'])) {
            $data['categories'] = json_encode($data['categories']);
        }

        $business->update($data);
        return response()->json($business);
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = Business::query();

        if ($request->has('term')) {
            $query->where('name', 'like', '%' . $request->term . '%');
        }
        if ($request->has('location')) {
            $query->where('city', 'like', '%' . $request->location . '%');
        }
        if ($request->has('latitude') && $request->has('longitude')) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $query->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                [$latitude, $longitude, $latitude, $request->radius ?? 50]
            );
        }
        if ($request->has('categories')) {
            $query->where('categories', 'like', '%' . $request->categories . '%');
        }
        if ($request->has('locale')) {
            // implement locale search logic if needed
        }
        if ($request->has('price')) {
            // implement price search logic if needed
        }
        if ($request->has('open_now')) {
            // implement open_now search logic if needed
        }
        if ($request->has('open_at')) {
            // implement open_at search logic if needed
        }
        if ($request->has('attributes')) {
            // implement attributes search logic if needed
        }

        $businesses = $query->paginate($request->limit ?? 15);

        return response()->json($businesses);
    }
}
