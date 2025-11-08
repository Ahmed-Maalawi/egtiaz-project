<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Laravolt\Avatar\Facade as Avatar;

class ModeratorsController extends Controller
{
    /**
     * List all moderators
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = User::with(['paymentAccounts', 'permissions', 'companyOfModeration'])
            ->role('moderator');

        if ($user->hasRole('admin') && $user->companyOfModeration) {
            $query->where('moderator_company_id', $user->companyOfModeration->id);
        }

        $moderators = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'Moderators retrieved successfully.',
            'data' => $moderators
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $moderator = User::role('moderator')
            ->with(['permissions', 'paymentAccounts', 'companyOfModeration'])
            ->find($id);

        if (!$moderator) {
            return response()->json([
                'status' => false,
                'message' => 'Moderator not found.'
            ], 404);
        }

        if ($user->hasRole('admin') && !$user->hasRole('super-admin')) {
            if ($moderator->moderator_company_id !== $user->companyOfModeration?->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. You can only view moderators in your company.'
                ], 403);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Moderator retrieved successfully.',
            'data' => $moderator
        ]);
    }

    /**
     * Create moderator
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['super-admin', 'admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only super-admins or admins can create moderators.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name'                      => 'required|string|max:255',
                'email'                     => 'required|email|unique:users,email',
                'password'                  => 'required|min:8|confirmed',
                'image'                     => 'nullable|image|max:5120',
                'status'                    => 'required|in:active,inactive',
                'permissions'               => 'required|array',
                'permissions.*'             => 'required|exists:permissions,id',
                'paymentAccounts'           => 'nullable|array',
                'paymentAccounts.*'         => 'required|exists:payment_accounts,id',
                'moderator_company_id'      => 'required|exists:companies,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $e->errors(),
            ], 422);
        }

        $image_path = '';
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('users', 'public');
        } else {
            $avatar = Avatar::create($request->name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'users/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $image_path = $filename;
        }

        try {
            DB::beginTransaction();

            $moderator = User::create([
                'name'                  => $validated['name'],
                'email'                 => $validated['email'],
                'password'              => Hash::make($validated['password']),
                'image'                 => $image_path,
                'status'                => $validated['status'],
                'moderator_company_id'  => $validated['moderator_company_id'],
            ]);

            if (!empty($validated['paymentAccounts'])) {
                $moderator->paymentAccounts()->attach($validated['paymentAccounts']);
            }

            $moderator->assignRole('moderator');
            $moderator->permissions()->attach($validated['permissions']);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Moderator created successfully.',
                'data' => $moderator->load(['permissions', 'paymentAccounts', 'companyOfModeration'])
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update moderator
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['super-admin', 'admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only super-admins or admins can update moderators.'
            ], 403);
        }

        $moderator = User::role('moderator')->find($id);
        if (!$moderator) {
            return response()->json([
                'status' => false,
                'message' => 'Moderator not found.'
            ], 404);
        }

        if ($user->hasRole('admin') && $moderator->moderator_company_id !== $user->companyOfModeration?->id) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. You can only update moderators in your company.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name'                  => 'sometimes|required|string|max:255',
                'email'                 => 'sometimes|required|email|unique:users,email,' . $moderator->id,
                'password'              => 'nullable|min:8|confirmed',
                'image'                 => 'nullable|image|max:5120',
                'status'                => 'required|in:active,inactive',
                'permissions'           => 'required|array',
                'permissions.*'         => 'required|exists:permissions,id',
                'paymentAccounts'       => 'nullable|array',
                'paymentAccounts.*'     => 'required|exists:payment_accounts,id',
                'moderator_company_id'  => 'required|exists:companies,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                if ($moderator->image && Storage::disk('public')->exists($moderator->image)) {
                    Storage::disk('public')->delete($moderator->image);
                }
                $moderator->image = $request->file('image')->store('users', 'public');
            }

            $moderator->update([
                'name'                  => $validated['name'] ?? $moderator->name,
                'email'                 => $validated['email'] ?? $moderator->email,
                'status'                => $validated['status'],
                'password'              => isset($validated['password'])
                    ? Hash::make($validated['password'])
                    : $moderator->password,
                'moderator_company_id'  => $validated['moderator_company_id'] ?? $moderator->moderator_company_id,
            ]);

            $moderator->permissions()->sync($validated['permissions']);
            $moderator->paymentAccounts()->sync($validated['paymentAccounts'] ?? []);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Moderator updated successfully.',
                'data' => $moderator->load(['permissions', 'paymentAccounts', 'companyOfModeration'])
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete moderator
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['super-admin', 'admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only super-admins or admins can delete moderators.'
            ], 403);
        }

        $moderator = User::role('moderator')->find($id);
        if (!$moderator) {
            return response()->json([
                'status' => false,
                'message' => 'Moderator not found.'
            ], 404);
        }

        if ($user->hasRole('admin') && $moderator->moderator_company_id !== $user->companyOfModeration?->id) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. You can only delete moderators in your company.'
            ], 403);
        }

        try {
            if ($moderator->image && Storage::disk('public')->exists($moderator->image)) {
                Storage::disk('public')->delete($moderator->image);
            }

            $moderator->delete();

            return response()->json([
                'status' => true,
                'message' => 'Moderator deleted successfully.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
