<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Laravolt\Avatar\Facade as Avatar;
use Throwable;

class CompanyModeratorsController extends Controller
{
    public function index()
    {
        $moderators = User::with([
            'companyOfModeration'
        ])->role('moderator')->get();

        return view('admin.moderators.index', compact('moderators'));
    }

    public function create()
    {
        $paymentAccounts = PaymentAccount::get();
        return view('admin.moderators.create', compact('paymentAccounts'));
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
            'company_id'                => 'required|exists:companies,id',
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
            $moderator = User::create([
                'name'                          => $request->name,
                'email'                         => $request->email,
                'image'                         => $image_path,
                'password'                      => Hash::make($request->password),
                'status'                        => $request->status,
                'moderator_company_id'          => $request->company_id,
            ]);

            if ($request->has('paymentAccounts')) {
                dd($request->paymentAccounts);
                $moderator->paymentAccounts()->attach($request->paymentAccounts);
            }

            $moderator->assignRole('moderator');
            DB::commit();

            return redirect()->route('admins.moderators.index')
                ->with('success', __('Moderator Added '));
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function edit($id)
    {
        $moderator = User::with(['companyOfModeration', 'paymentAccounts'])->findOrFail($id);
        $paymentAccounts = PaymentAccount::get();

        return view('admin.moderators.edit', compact('moderator', 'paymentAccounts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                      => 'required|string|max:255',
            'email'                     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password'                  => ['nullable', 'required_with:password_confirmation', RulesPassword::min(8)],
            'password_confirmation'     => 'nullable|required_with:password|same:password',
            'status'                    => 'required|in:active,inactive',
            'image'                     => 'nullable|image|max:5120',
            'company_id'                => 'required|exists:companies,id',
            'paymentAccounts'           => 'nullable|array',
            'paymentAccounts.*'         => 'required|exists:payment_accounts,id',
        ]);

        $moderator = User::findOrFail($id);

        $image_path = $moderator->image;
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
            $moderator->update([
                'name'                          => $request->name,
                'email'                         => $request->email,
                'image'                         => $image_path,
                'password'                      => $request->filled('password') ? Hash::make($request->password) : $moderator->password,
                'status'                        => $request->status,
                'moderator_company_id'          => $request->company_id,
            ]);

            $moderator->paymentAccounts()->sync($request->paymentAccounts);

            return redirect()->route('admins.moderators.index')
                ->with('success', __('Moderator Updated '));
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)
    {
        $moderator = User::findOrFail($id);

        if ($moderator->image) {
            Controller::deleteFile($moderator->image);
        }
        $moderator->delete();

        return redirect()->route('admins.moderators.index')
            ->with('success', __('Moderator Deleted'));
    }
}
