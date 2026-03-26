<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScanGroup;
use Illuminate\Http\Request;

class ScanGroupController extends Controller
{
    /**
     * 📄 Listar grupos
     */
    public function index()
    {
        $groups = ScanGroup::withCount('users')->latest()->get();

        return response()->json([
            'data' => $groups
        ]);
    }

    /**
     * 🔍 Ver grupo con miembros
     */
    public function show($id)
    {
        $group = ScanGroup::with('users')->find($id);

        if (!$group) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'data' => $group
        ]);
    }

    /**
     * 📥 Crear grupo
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url'
        ]);

        $group = ScanGroup::create([
            'name' => $request->name,
            'website' => $request->website
        ]);

        // 👑 El creador es owner
        $group->users()->attach($request->user()->id, [
            'role' => 'owner'
        ]);

        return response()->json([
            'message' => 'Scan group created',
            'data' => $group
        ], 201);
    }

    /**
     * ➕ Agregar usuario al grupo
     */
    public function addUser(Request $request, $groupId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string'
        ]);

        $group = ScanGroup::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        // 🔐 Solo owner o admin
        $user = $request->user();

        if (
            !$user->hasRole('admin') &&
            !$user->hasScanRole($groupId, 'owner')
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->users()->syncWithoutDetaching([
            $request->user_id => ['role' => $request->role]
        ]);

        return response()->json([
            'message' => 'User added to group'
        ]);
    }

    /**
     * 🔄 Actualizar rol
     */
    public function updateUserRole(Request $request, $groupId, $userId)
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $group = ScanGroup::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $user = $request->user();

        if (
            !$user->hasRole('admin') &&
            !$user->hasScanRole($groupId, 'owner')
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->users()->updateExistingPivot($userId, [
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'Role updated'
        ]);
    }

    /**
     * ❌ Quitar usuario del grupo
     */
    public function removeUser(Request $request, $groupId, $userId)
    {
        $group = ScanGroup::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $user = $request->user();

        if (
            !$user->hasRole('admin') &&
            !$user->hasScanRole($groupId, 'owner')
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->users()->detach($userId);

        return response()->json([
            'message' => 'User removed from group'
        ]);
    }

    /**
     * ❌ Eliminar grupo
     */
    public function destroy(Request $request, $id)
    {
        $group = ScanGroup::find($id);

        if (!$group) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $user = $request->user();

        if (
            !$user->hasRole('admin') &&
            !$user->hasScanRole($id, 'owner')
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->delete();

        return response()->json([
            'message' => 'Group deleted'
        ]);
    }
}
