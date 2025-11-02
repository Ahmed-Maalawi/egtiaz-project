<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Helpers\CalculationsHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderBranch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'client_qr_code'                => 'nullable|required_without:card_code|exists:users,qr_code',
            'card_code'                     => 'nullable|required_without:client_qr_code|exists:users,card_code',
            'sum'                           => 'required|numeric|min:1',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (! $user->hasRole('cashier')) {
            return response()->json([
                'message'               => __('User Must Be Cashier To Perform This Action')
            ], 403);
        }

        $branch = ServiceProviderBranch::where('id',$user->service_provider_branch_id)->first();
        if (! $branch) {
            return response()->json([
                'message' => __('Service Provider Branch Not Found.')
            ], 404);
        }
        $service_provider = ServiceProvider::where('id', $branch->service_provider_id)->first();

        $client = User::where('qr_code', $request->input('client_qr_code'))->orWhere('card_code', $request->card_code)->first();

        if (! $client) {
            return response()->json([
                'message' => __('Client not found.')
            ], 404);
        }

        if ($client->status != 'active') {
            return response()->json([
                'message'               => __('Client Banned By Admin')
            ], 403);
        }

        if ($client->hasRole('cashier')) {
            return response()->json([
                'message' => __('Only Normal Users Allowed To Proceed.')
            ], 403);
        }

        $calculations = CalculationsHelper::calculatePayableAmount($client->payments_total, $client->orders_count, $request->sum, $service_provider);

        $payable_amount = $calculations['totalAfterDiscount'];

        $order = Order::create([
            'user_id'                               => $client->id,
            'service_provider_id'                   => $service_provider->id,
            'service_provider_name'                 => [
                'en'                                        => $service_provider->getTranslation('name', 'en'),
                'ar'                                        => $service_provider->getTranslation('name', 'ar'),
            ],
            'sum'                                   => $calculations['total'],
            'applicable_discount'                   => $calculations['discountedAmount'],
            'applicable_discount_percentage'        => $calculations['total'] > 0
                ? round($calculations['discountedAmount'] / $calculations['total'] * 100, 2)
                : 0,
            'profit'                                => $calculations['a2zProfit'],
            'profit_percentage'                     => $calculations['a2zPercentage'],
            'cashier_id'                            => $user->id,
            'cashier_name'                          => $user->name,
            'service_provider_branch_id'            => $branch->id,
            'service_provider_branch_address'       => [
                'en'                                        => $branch->getTranslation('address', 'en'),
                'ar'                                        => $branch->getTranslation('address', 'ar'),
            ],
            'status'                                => 'pending',
        ]);

        Event::dispatch(new OrderCreated($client, $payable_amount, $service_provider));

        return response()->json([
            'payableAmount'                 => $payable_amount,
            'order'                         => new OrderResource($order->load('serviceProvider')),
        ]);
    }

    public function confirmOrderFromCashier($id)
    {
        $order = Order::where('id', $id)->first();

        $user = Auth::guard('sanctum')->user();

        if (! $user->hasRole('cashier')) {
            return response()->json([
                'message'               => __('User Must Be Cashier To Perform This Action')
            ], 403);
        }

        if ($user->id != $order->cashier_id) {
            return response()->json([
                'message'               => __('Cant Confirm From Other Cashier')
            ], 403);
        }

        $order->status = 'success';
        $order->save();

        return response()->json([
            'message'               => __('Order Status Updated'),
            'order'                 => new OrderResource(Order::with(['serviceProvider'])->where('id', $id)->first())
        ]);
    }

    public function confirmOrderFromUser($id)
    {
        $order = Order::where('id', $id)->first();

        $user = Auth::guard('sanctum')->user();

        if ($user->roles()->exists()) {
            return response()->json([
                'message' => __('Only Normal Users Allowed To Proceed.')
            ], 403);
        }

        if ($user->id != $order->user_id) {
            return response()->json([
                'message'               => __('Cant Confirm From Other User')
            ], 403);
        }

        $order->status = 'success';
        $order->save();

        return response()->json([
            'message'               => __('Order Status Updated'),
            'order'                 => new OrderResource(Order::with(['serviceProvider'])->where('id', $id)->first())
        ]);
    }

    public function myOrders()
    {
        $user = Auth::guard('sanctum')->user();

        if ($user->hasRole('cashier')) {
            $orders = Order::with(['serviceProvider'])->where('cashier_id', $user->id)->latest()->paginate(10);
            return OrderResource::collection($orders);
        }

        $orders = Order::with(['serviceProvider'])->where('user_id', $user->id)->latest()->paginate(10);
        return OrderResource::collection($orders);
    }

    public function pendingOrders()
    {
        $user = Auth::guard('sanctum')->user();

        $orders = Order::with('serviceProvider')->where('user_id',$user->id)
        ->where('status','pending')->latest()->get();

        return OrderResource::collection($orders);
    }
}
