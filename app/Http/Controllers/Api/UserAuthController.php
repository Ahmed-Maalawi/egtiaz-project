<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\UserResource;
use App\Models\Card;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Laravolt\Avatar\Facade as Avatar;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'                      => 'required|string|max:255',
            'phone_number'              => 'required|string|max:15',
            'country_code'              => 'nullable|string|max:5',
            'sex'                       => 'nullable|in:f,m',
            'image'                     => 'nullable|image|max:2048',
            'password'                  => ['required', Password::min(8)],
            're_password'               => 'required|same:password',
            'referral_code'             => 'nullable|exists:users,referral_code',
            'city_id'                   => 'required|exists:cities,id',
        ]);


        $country_code = '+20';
        if ($request->has('country_code')) {
            $country_code = $request->country_code;
        }

        $matched = preg_match('/^\+201[0-9]{9}$/', $country_code . $request->phone_number);

        if (! $matched) {
            return response()->json([
                'message'                   => __('Invalid Phone Number'),
            ], 403);
        }

        if (User::where('country_code', $country_code)->where('phone_number', $request->phone_number)->exists()) {
            return response()->json([
                'message'                   => __('phone number already taken'),
            ], 403);
        }

        $image_path = '';
        if ($request->hasFile('image') && !$request->remove_image) {
            $image = $request->file('image');
            $image_path = $image->store('users', 'public');
        } else {
            $avatar = Avatar::create($request->name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'users/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $image_path = $filename;
        }

        $user = User::create([
            'name'                      => $request->name,
            'country_code'              => $country_code,
            'phone_number'              => $request->phone_number,
            'sex'                       => $request->sex ?? 'm',
            'image'                     => $image_path,
            'password'                  => Hash::make($request->password),
            'referred_by'               => $request->referral_code,
            'city_id'                   => $request->city_id,
        ]);

        $auth_token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'                   => __('User Registered Successfully'),
            'user'                      => new UserResource($user->load('city')),
            'auth_token'                => $auth_token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('Invalid Email Or Password'),
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'message' => __('User Banned By Admins'),
            ], 401);
        }

        $auth_token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'     => __('Logged in successfully'),
            'user'        => new UserResource($user),
            'auth_token'  => $auth_token,
        ]);
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();

        if (! $user) {
            return response()->json([
                'message'   => __('Unauthenticated'),
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'message'   => __('Logged out'),
        ], 204);
    }

    public function updateTokens(Request $request)
    {
        $request->validate([
            'fcm_token'                     => 'required_without:player_id|string|max:255',
            'player_id'                     => 'required_without:fcm_token|string|max:255',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (! $user) {
            return response()->json([
                'message'                   => __('Unauthenticated'),
            ], 401);
        }

        if ($request->has('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
        }

        if ($request->has('player_id')) {
            $user->player_id = $request->player_id;
        }

        $user->save();

        return response()->json([
            'message'               => __('user tokens updated')
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'        => "sometimes|required|max:255",
            'sex'         => 'sometimes|required|in:f,m',
            'image'       => 'sometimes|nullable|image|max:2048',
            'password'    => ['sometimes', 'required', Password::min(8)],
            're_password' => 'sometimes|required_with:password|same:password',
            'city_id'       => 'sometimes|exists:cities,id'
        ]);

        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => __('Unauthenticated')], 401);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('users', 'public');
            if ($user->image && !$this->isAvatarImage($user->image)) {
                Controller::deleteFile($user->image);
            }
            $user->image = $image_path;
        } elseif (is_null($request->image) && $this->isAvatarImage($user->image)) {
            $name = $request->name ?? $user->name;
            $avatar = Avatar::create($name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'users/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $user->image = $filename;
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('city_id')) {
            $user->city_id = $request->city_id;
        }

        if ($request->filled('sex')) {
            $user->sex = $request->sex;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => __('User updated')]);
    }

    private function isAvatarImage(string $imagePath): bool
    {
        return preg_match('/users\/[a-f0-9]{13,}\.png$/', $imagePath);
    }

    public function myQrcode()
    {
        $user = Auth::guard('sanctum')->user();

        return response()->json($user->qr_code);
    }

    public function myReferralCode()
    {
        $user = Auth::guard('sanctum')->user();

        return response()->json($user->referral_code);
    }

    public function myCard()
    {
        $user = Auth::guard('sanctum')->user();

        $card = Card::getApplicableCard($user->payments_total, $user->orders_count);

        return response()->json([
            'card'                  => new CardResource($card),
            'user'                  => [
                'name'                  => $user->name,
                'phone'                 => $user->phone,
                'card_code'             => $user->card_code,
                'qr_code'               => $user->qr_code,
                'image'                 => $user->image_url,
                'expired_at'            => Carbon::parse($user->created_at)->addYear(),
            ]
        ]);
    }

    public function user()
    {
        return new UserResource(Auth::guard('sanctum')->user());
    }

    public function deleteAccount()
    {
        $user = Auth::guard('sanctum')->user();

        if($user->image){
            Controller::deleteFile($user->image);
        }

        $user->delete();

        return response()->json(status:204);
    }
}
