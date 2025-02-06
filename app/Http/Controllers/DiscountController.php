<?php

namespace App\Http\Controllers;

use App\Services\DiscountService;
use App\Models\Order;
use App\Helpers\ApiResponse;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function calculate($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);
        $discountData = $this->discountService->calculateDiscount($order);

        return ApiResponse::success("İndirim başarıyla hesaplandı.", $discountData);
    }
}