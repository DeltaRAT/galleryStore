<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PayRequest;
use App\Mail\SendOrderedImages;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\Payment\paymentService;
use App\Services\Payment\Requests\IDPayRequest;
use App\Services\Payment\Requests\IDPayVerifyRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function pay(PayRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::firstOrCreate([
            'email' => $validatedData['email']
        ], [
            'name' => $validatedData['name'],
            'mobile' => $validatedData['mobile']
        ]);

        try {
            $orderItems = json_decode(Cookie::get('basket'), true);

            if (count($orderItems) <= 0) {
                throw new \InvalidArgumentException('سبد خرید شما خالی است!');
            }

            $products = Product::findMany(array_keys($orderItems));

            $productsPrice = $products->sum('price');

            $ref_code = Str::random(30);

            $createdOrder = Order::create([
                'amount' => $productsPrice,
                'ref_code' => $ref_code,
                'status' => 'unpaid',
                'user_id' => $user->id
            ]);

            $orderItemsForCreatedOrder = $products->map(function ($product) {
                $currentProduct = $product->only(['price', 'id']);

                $currentProduct['product_id'] = $currentProduct['id'];

                unset($currentProduct['id']);

                return $currentProduct;
            });

            $createdOrder->orderItem()->createMany($orderItemsForCreatedOrder->toArray());

            $createdPayment = Payment::create([
                'gateway' => 'idpay',
                'ref_code' => $ref_code,
                'status' => 'unpaid',
                'order_id' => $createdOrder->id
            ]);

            $idPayRequest = new IDPayRequest([
                'amount' => $productsPrice,
                'user' => $user,
                'order_id' => $ref_code,
                'apiKey' => config('services.gateways.id_pay.api_key')
            ]);
            $paymentService = new paymentService(paymentService::IDPAY, $idPayRequest);

            return $paymentService->pay();

        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }

    }

    public function callback(Request $request)
    {
        $paymentInfo = $request->all();

        $idPayVerifyRequest = new IDPayVerifyRequest([
            'id' => $paymentInfo['id'],
            'order_id' => $paymentInfo['order_id'],
            'apiKey' => config('services.gateways.id_pay.api_key')
        ]);

        $paymentService = new paymentService(paymentService::IDPAY, $idPayVerifyRequest);

        $result = $paymentService->verify();

        if (!$result['status'])
        {
            return redirect()->route('home.checkout')->with('failed', 'پرداخت شما انجام نشد');
        }
//        if ($result['statusCode'] == 101)
//        {
//            return redirect()->route('home.checkout')->with('failed','پرداخت انحام شده است و تصاویر برای شما ایمیل شده است!');
//        }

        $currentPayment = Payment::where('ref_code', $result['data']['order_id'])->first();

        $currentPayment->update([
            'status' => 'paid',
            'res_id' => $result['data']['track_id']
        ]);

        $currentPayment->order()->update([
            'status' => 'paid'
        ]);

        $currentUser = $currentPayment->order->user;

         $reservedImages = $currentPayment->order->orderItem->map(function ($orderItem){
             return $orderItem->product->source_url;
         });

         Mail::to($currentUser)->send(new SendOrderedImages($reservedImages->toArray(), $currentUser));

         Cookie::queue('basket', null);

         return redirect()->route('home.products.all')->with('success', 'پرداخت شما انجام شد و تصاویر برای شما ایمیل شدند');

    }
}
