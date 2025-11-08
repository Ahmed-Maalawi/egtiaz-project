<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\Company;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravolt\Avatar\Avatar;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = User::with(['paymentAccounts', 'permissions'])->role('admin');

        if (!$user->hasRole(['super-admin', 'admin']) && $user->companyOfModeration?->id) {
            $companyId = $user->companyOfModeration->id;

            $query->whereHas('companyOfModeration', function ($q) use ($companyId) {
                $q->where('companies.id', $companyId);
            });
        }

        $admins = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'message' => 'get all admins',
            'data' => AdminResource::collection($admins),
            'meta' => [
                'current_page' => $admins->currentPage(),
                'last_page'    => $admins->lastPage(),
                'per_page'     => $admins->perPage(),
                'total'        => $admins->total(),
            ],
        ]);
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|min:8|confirmed',
                'image'                 => 'nullable|image|max:5120',
                'status'                => 'required|in:active,inactive',
                'permissions'           => 'required|array',
                'permissions.*'         => 'required|exists:permissions,id',
                'paymentAccounts'       => 'nullable|array',
                'paymentAccounts.*'     => 'required|exists:payment_accounts,id',
            ]);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => __('Validation failed'),
                'errors' => $e->errors(),
            ], 422);
        }

        $image_path = $this->storeImageOrAvatar($request);

        try {
            DB::beginTransaction();

            $admin = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'image'     => $image_path,
                'status'    => $validated['status'],
            ]);

            $admin->assignRole('admin');
            $admin->permissions()->attach($validated['permissions']);

            if (!empty($validated['paymentAccounts'])) {
                $admin->paymentAccounts()->attach($validated['paymentAccounts']);
            }

            DB::commit();

            return response()->json([
                'message' => __('Admin created successfully'),
                'data' => new AdminResource($admin->load('permissions', 'paymentAccounts'))
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function show(User $admin)
    {
        $admin->load(['permissions', 'paymentAccounts']);
        return new AdminResource($admin);
    }

    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
                'password'          => ['nullable', 'confirmed', Password::min(8)],
                'status'            => 'required|in:active,inactive',
                'permissions'       => 'required|array',
                'permissions.*'     => 'required|exists:permissions,id',
                'image'             => 'nullable|image|max:5120',
                'paymentAccounts'   => 'nullable|array',
                'paymentAccounts.*' => 'required|exists:payment_accounts,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => __('Validation failed'),
                'errors' => $e->errors(),
            ]);
        }

        $admin = User::role('admin')->where('id', 18)->first();

        if ($request->hasFile('image')) {
            if ($admin->image && !$this->isAvatarImage($admin->image)) {
                Storage::disk('public')->delete($admin->image);
            }
            $admin->image = $request->file('image')->store('users', 'public');
        }

        try {
            DB::beginTransaction();

            $admin->update([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'status'    => $validated['status'],
                'password'  => !empty($validated['password']) ? Hash::make($validated['password']) : $admin->password,
                'image'     => $admin->image,
            ]);

            $admin->permissions()->sync($validated['permissions']);
            $admin->paymentAccounts()->sync($validated['paymentAccounts'] ?? []);

            DB::commit();

            return response()->json([
                'message' => __('Admin updated successfully'),
                'data' => new AdminResource($admin->load('permissions', 'paymentAccounts'))
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function destroy(User $admin)
    {
        try {
            if ($admin->image) {
                Storage::disk('public')->delete($admin->image);
            }
            $admin->delete();

            return response()->json(['message' => __('Admin deleted successfully')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    private function storeImageOrAvatar(Request $request): string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('users', 'public');
        }

        $avatar = Avatar::create($request->name)->toBase64();
        $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
        $filename = 'users/' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $image_content);
        return $filename;
    }

    private function isAvatarImage(string $imagePath): bool
    {
        return preg_match('/users\/[a-f0-9]{13,}\.png$/', $imagePath);
    }
}
