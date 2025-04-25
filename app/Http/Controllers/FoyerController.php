<?php

namespace App\Http\Controllers;

use App\Models\Foyer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FoyerController extends Controller
{
    /**
     * Display a listing of the foyers.
     */
    public function index()
    {
        $foyers = Foyer::with(['chief', 'workers'])->get();
        return view('supervisor.foyers', compact('foyers'));
    }

    /**
     * Store a newly created foyer in the database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'chief_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $foyer = Foyer::create([
            'name' => $request->name,
            'description' => $request->description,
            'chief_id' => $request->chief_id,
            'stat' => $request->stat ?? 1, // Default active status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Foyer created successfully',
            'data' => $foyer->load(['chief', 'workers'])
        ], 201);
    }

    /**
     * Display the specified foyer.
     */
    public function show($id)
    {
        $foyer = Foyer::with(['chief', 'workers'])->findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $foyer
        ]);
    }

    /**
     * Update the specified foyer in the database.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'chief_id' => 'required|exists:users,id',
            'stat' => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $foyer = Foyer::findOrFail($id);
        $foyer->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Foyer updated successfully',
            'data' => $foyer->fresh()->load(['chief', 'workers'])
        ]);
    }

    /**
     * Remove the specified foyer from storage.
     */
    public function destroy($id)
    {
        $foyer = Foyer::findOrFail($id);
        $foyer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Foyer deleted successfully'
        ]);
    }

    /**
     * Get all available users that can be assigned as foyer chiefs
     */
    public function getAvailableChiefs()
    {
        $availableChiefs = User::whereNull('foyer_id')
            ->orWhere('id', Auth::id()) // Include current user
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json([
            'status' => true,
            'data' => $availableChiefs
        ]);
    }

    /**
     * Add a worker to a foyer
     */
    public function addWorker(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $foyer = Foyer::findOrFail($id);
        $user = User::findOrFail($request->user_id);
        
        // Update user's foyer_id
        $user->update(['foyer_id' => $foyer->id]);

        return response()->json([
            'status' => true,
            'message' => 'Worker added successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove a worker from a foyer
     */
    public function removeWorker($foyerId, $userId)
    {
        $user = User::where('id', $userId)
            ->where('foyer_id', $foyerId)
            ->firstOrFail();
            
        // Remove user from foyer
        $user->update(['foyer_id' => null]);

        return response()->json([
            'status' => true,
            'message' => 'Worker removed successfully'
        ]);
    }
}
