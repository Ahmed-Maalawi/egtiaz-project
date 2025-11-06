<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\WalletResource;
use App\Models\Company;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {

            $wallets = Wallet::all();

        } else {

            $wallets = Wallet::where('company_id', $user->moderator_company_id)->get();
        }

        return response()->json([
            'data' => WalletResource::collection($wallets),
            'success' => true,
        ]);
    }

    public function show($id)
    {
        try {
            $user = Auth::user();

            $wallet = Wallet::with(['company', 'transactions'])
                ->find($id);

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet not found'
                ], 404);
            }

            if (!$user->hasRole('super-admin') && $user->moderator_company_id != $wallet->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to access this wallet'
                ], 403);
            }

            return response()->json([
                'wallet' => new WalletResource($wallet),
                'success' => true,
            ]);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wallet details'
            ], 500);
        }
    }

}
