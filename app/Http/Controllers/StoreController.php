<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreInfo;

class StoreController extends Controller
{
    public function show(Request $request)
    {
        $store = StoreInfo::where('user_id', $request->user()->id)->first();

        if (!$store) {
            return response()->json(['message' => 'Store info not found'], 404);
        }

        return response()->json($store);
    }

            public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'website' => 'nullable|string',
            ]);

            // Simpan data toko ke database
            $store = StoreInfo::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
            ]);


            return response()->json([
                'message' => 'Store info saved successfully',
                'data' => $store
            ], 201);
        }


    public function update(Request $request)
    {
        $store = StoreInfo::where('user_id', $request->user()->id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:100',
        ]);

        $store->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
        ]);

        return response()->json(['message' => 'Store info updated', 'data' => $store]);
    }
}
