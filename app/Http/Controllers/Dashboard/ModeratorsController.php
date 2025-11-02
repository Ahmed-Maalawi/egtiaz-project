<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Laravolt\Avatar\Facade as Avatar;

class ModeratorsController extends Controller
{
    public function index(Request $request)
    {
        $admins = User::with(['paymentAccounts', 'permissions'])->role('admin')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $permissions = Permission::get();
        $paymentAccounts = PaymentAccount::get();

        return view('admin.admins.create', compact('permissions', 'paymentAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                      => 'required|string|max:255',
            'email'                     => 'required|email|unique:users,email',
            'password'                  => 'required|min:8',
            'password_confirmation'     => 'required|same:password',
            'image'                     => 'nullable|image|max:5120',
            'status'                    => 'required|in:active,inactive',
            'permissions'               => 'required|array',
            'permissions.*'             => 'required|exists:permissions,id',
            'paymentAccounts'           => 'nullable|array',
            'paymentAccounts.*'         => 'required|exists:payment_accounts,id',
        ]);

        $image_path = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('users', 'public');
        } else {
            $avatar = Avatar::create($request->name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'users/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $image_path = $filename;
        }

        try {
            DB::beginTransaction();

            $admin = User::create([
                'name'                      => $request->name,
                'email'                     => $request->email,
                'password'                  => Hash::make($request->password),
                'image'                     => $image_path,
                'status'                    => $request->status,
            ]);

            if ($request->has('paymentAccounts')) {
                $admin->paymentAccounts()->attach($request->paymentAccounts);
            }

            $admin->assignRole('admin');

            $admin->permissions()->attach($request->permissions);

            DB::commit();

            return redirect()->route('admins.admins.index')
                ->with('success',__('Admin Added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
