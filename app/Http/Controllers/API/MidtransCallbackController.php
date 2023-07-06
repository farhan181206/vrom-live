<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Booking;

class MidtransCallbackController extends Controller
{
    public function callback()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
        // buat intence midtrans notification
        $notification = new Notification();
        // assign variable untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment->type;
        $fraud = $notification->fraund_status;
        $order_id = $notification->order_id;
        // get transaction id
        $order = explode('-' , $order_id);
        // cari transaction berdasarkan id
        $booking = Booking::findOrFail($order[1]);
        // handle notification status midtrans
        if($status == 'capture'){
            if($type == 'credit_card'){
                if($fraud == 'challenge'){
                    $booking->status = 'PENDING';
                }
                else{
                    $booking->status = 'SUCCESS';
                }
            }
        }
        else if($status == 'settlement')
        {
            $booking->status = 'SUCCESS';
        }
        else if($status == 'PENDING')
        {
            $booking->status = 'PENDING';
        }
        else if($status == 'deny')
        {
            $booking->status = 'PENDING';
        }
        else if($status == 'expire')
        {
            $booking->status = 'CANCELLED';
        }
        else if($status == 'cancel')
        {
            $booking->status = 'CANCELLED';
        }
        // simpan booking 
        $transaction->save();
        // return response untuk midtrans
        return response()->json([
            'code' => 200,
            'message' => 'Midtrans Notification Success'
        ]);
    }
}
