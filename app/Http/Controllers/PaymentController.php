<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use ErrorException;

class PaymentController extends Controller
{
    public function index(Photo $photo)
    {
        session()->put('order', [
            'photo_id' => $photo->id,
            'qty' => 1,
            'total' => $photo->price
        ]);
        return view('payments.index')->with([
            'photo' => $photo
        ]);
    }


    public function pay()
    {
        \Stripe\Stripe::setApiKey('YOUR SECRET KEY');

        try {
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $this->calculateOrderAmount($jsonObj->items),
                'currency' => 'usd',
                'description' => 'Laravel Images Stock',
                'setup_future_usage' => 'on_session',
                'metadata' => [
                    'user_id' => auth()->user()->id
                ]
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret
            ];

            return response()->json($output);

        } catch (ErrorException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function calculateOrderAmount(array $items)
    {
        foreach ($items as $item) {
            return $item->amount * 100;
        }
    }

    public function success()
    {
        $order = session()->get('order');
        auth()->user()->orders()->create($order);
        session()->remove('order');
        return redirect()->route('photos.show', $order['photo_id'])->with([
            'success' => 'Payment placed successfully'
        ]);
    }
}
