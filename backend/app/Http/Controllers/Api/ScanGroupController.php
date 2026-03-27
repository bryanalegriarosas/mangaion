<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\ScanGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanGroupController extends Controller
{
    /**
     * 📄 Listar grupos
     */
    public function index(): JsonResponse
    {
        $groups = ScanGroup::withCount('users')->latest()->get();

        return response()->json([
            'data' => $groups
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 🔍 Ver grupo con miembros
     */
    public function show($id): JsonResponse
    {
        $group = ScanGroup::with('users')->find($id);

        if (!$group) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'data' => $group
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 📥 Crear grupo
     */
    public function store(Request $request): JsonResponse
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
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * ➕ Agregar usuario al grupo
     */
    public function addUser(Request $request, $groupId): JsonResponse
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
            !$user->hasRole(Role::SUPER_ADMIN) &&
            !$user->hasScanRole($groupId, Role::OWNER)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->users()->syncWithoutDetaching([
            $request->user_id => ['role' => $request->role]
        ]);

        return response()->json([
            'message' => 'User added to group'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * 🔄 Actualizar rol
     */
    public function updateUserRole(Request $request, $groupId, $userId): JsonResponse
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
            !$user->hasRole(Role::SUPER_ADMIN) &&
            !$user->hasScanRole($groupId, Role::OWNER)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->users()->updateExistingPivot($userId, [
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'Role updated'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * ❌ Quitar usuario del grupo
     */
    public function removeUser(Request $request, $groupId, $userId): JsonResponse
    {
        $group = ScanGroup::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $user = $request->user();

        if (
            !$user->hasRole(Role::SUPER_ADMIN) &&
            !$user->hasScanRole($groupId, Role::OWNER)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->users()->detach($userId);

        return response()->json([
            'message' => 'User removed from group'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * ❌ Eliminar grupo
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $group = ScanGroup::find($id);

        if (!$group) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $user = $request->user();

        if (
            !$user->hasRole(Role::SUPER_ADMIN) &&
            !$user->hasScanRole($id, Role::OWNER)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $group->delete();

        return response()->json([
            'message' => 'Group deleted'
        ], JsonResponse::HTTP_OK);
    }
}
