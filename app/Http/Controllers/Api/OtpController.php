<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class OtpController extends Controller
{
    public function resetPassword(Request $request)
    {
    $request->validate([
        'country_code'    => 'required|string|max:5',
        'phone_number'    => 'required|string|max:15',
        'otp'             => 'required|digits:5',
        'password'        => 'required|string|min:8',
    ]);

    $user = User::where('country_code', $request->country_code)
        ->where('phone_number', $request->phone_number)
        ->first();

    if (! $user || $user->otp_code !== $request->otp || now()->gt($user->otp_expires_at)) {
        return response()->json([
            'message' => __('Invalid or expired OTP.'),
        ], 403);
    }

    $user->password = bcrypt($request->password);
    $user->otp_code = null;
    $user->otp_expires_at = null;
    $user->save();

    return response()->json([
        'message' => __('Password has been reset successfully.'),
    ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'country_code'              =>'required|string|max:5',
            'phone_number'              =>'required|string|max:15',
            ]);
            
        $user = User::where('country_code',$request->country_code)
        ->where('phone_number',$request->phone_number)->first();
        
        if(! $user){
            return response()->json([
                'message'               =>__('Phone Number Not Regitered'),
                ],403);
        }
        
        $otp = random_int(10000, 99999);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        $account_id = trim(config('services.vodafone.account_id'));
        $password = trim(config('services.vodafone.password'));
        $sender_name = trim(config('services.vodafone.sender_name'));
        $secure_key = trim(config('services.vodafone.secure_key'));
        $send_phone = '20' . ltrim($user->phone_number, '0');

        $sms_text = "Your OTP is:$otp"; 
        $otp_text = htmlspecialchars($sms_text, ENT_XML1, 'UTF-8');
    
        $concat = "AccountId=$account_id&Password=$password&SenderName=$sender_name&ReceiverMSISDN=$send_phone&SMSText=$sms_text";
    
        $hash = strtoupper(hash_hmac('sha256', $concat, $secure_key));
        
        $xml = trim(<<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <SubmitSMSRequest xmlns="http://www.edafa.com/web2sms/sms/model/">
            <AccountId>$account_id</AccountId>
            <Password>$password</Password>
            <SecureHash>$hash</SecureHash>
            <SMSList>
                <SenderName>$sender_name</SenderName>
                <ReceiverMSISDN>$send_phone</ReceiverMSISDN>
                <SMSText>$otp_text</SMSText>
            </SMSList>
        </SubmitSMSRequest>
        XML);

        $response = Http::withHeaders([
            'Content-Type' => 'application/xml',
        ])->withBody($xml, 'application/xml')
          ->post('https://e3len.vodafone.com.eg/web2sms/sms/submit/');

        return response()->json([
            'message' => 'OTP sent',
            'api_response' => $response->body()
        ]);
    }
public function send(Request $request)
{

    $user = Auth::guard('sanctum')->user();

    $otp = random_int(10000, 99999);

    $user->otp_code = $otp;
    $user->otp_expires_at = now()->addMinutes(5);
    $user->save();

    $account_id = trim(config('services.vodafone.account_id'));
    $password = trim(config('services.vodafone.password'));
    $sender_name = trim(config('services.vodafone.sender_name'));
    $secure_key = trim(config('services.vodafone.secure_key'));
    $send_phone = '20' . ltrim($user->phone_number, '0');

    $sms_text = "Your OTP is:$otp"; 
    $otp_text = htmlspecialchars($sms_text, ENT_XML1, 'UTF-8');
    
    $concat = "AccountId=$account_id&Password=$password&SenderName=$sender_name&ReceiverMSISDN=$send_phone&SMSText=$sms_text";

    $hash = strtoupper(hash_hmac('sha256', $concat, $secure_key));


    $xml = trim(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SubmitSMSRequest xmlns="http://www.edafa.com/web2sms/sms/model/">
    <AccountId>$account_id</AccountId>
    <Password>$password</Password>
    <SecureHash>$hash</SecureHash>
    <SMSList>
        <SenderName>$sender_name</SenderName>
        <ReceiverMSISDN>$send_phone</ReceiverMSISDN>
        <SMSText>$otp_text</SMSText>
    </SMSList>
</SubmitSMSRequest>
XML);

    \Log::info('SecureHash: ' . $hash);
    \Log::info('XML Sent: ' . $xml);

    $response = Http::withHeaders([
        'Content-Type' => 'application/xml',
    ])->withBody($xml, 'application/xml')
      ->post('https://e3len.vodafone.com.eg/web2sms/sms/submit/');

    return response()->json([
        'message' => 'OTP sent',
        'api_response' => $response->body()
    ]);
}


    public function verify(Request $request)
    {
        $request->validate([
            'code'                  => 'required|numeric|between:10000,99999'
        ]);

        $user = FacadesAuth::user();

        if ($user->otp_expires_at < now()) {
            return response()->json([
                'message'                   => __('Expired Code')
            ], 401);
        }

        if ($user->otp_code != $request->code) {
            return response()->json([
                'message'                   => __('Invalid Code')
            ], 401);
        }

        $user->phone_verified_at = now();
        $user->save();

        return response()->json([
            'message'               =>__('Phone verified succesfully')
        ]);
    }
    
}
