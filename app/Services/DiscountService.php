<?php

namespace App\Services;

use App\Models\Order;

class DiscountService
{
    public function calculateDiscount(Order $order)
    {
        $discounts = [];
        $totalDiscount = 0;
        $subtotal = $order->total;

        // Kural 1: 1000 TL ve üzeri alışverişte %10 indirim
        $this->applyTenPercentDiscountOver1000($order, $discounts, $totalDiscount, $subtotal);

        // Kural 2: 2 ID'li kategoriden 6 adet alınırsa 1 adet ücretsiz
        $this->applyBuy5Get1Discount($order, $discounts, $totalDiscount, $subtotal);

        // Kural 3: 1 ID'li kategoriden iki veya daha fazla ürün alındığında en ucuz ürüne %20 indirim
        $this->applyCheapestItemDiscount($order, $discounts, $totalDiscount, $subtotal);

        return [
            'orderId'         => $order->id,
            'discounts'       => $discounts,
            'totalDiscount'   => number_format($totalDiscount, 2, '.', ''),
            'discountedTotal' => number_format($subtotal, 2, '.', '')
        ];
    }

    protected function applyTenPercentDiscountOver1000(Order $order, &$discounts, &$totalDiscount, &$subtotal)
    {
        if ($order->total >= 1000) {
            $discountAmount = round($order->total * 0.10, 2);
            $subtotal -= $discountAmount;
            $discounts[] = [
                'discountReason' => '10_PERCENT_OVER_1000',
                'discountAmount' => number_format($discountAmount, 2, '.', ''),
                'subtotal'       => number_format($subtotal, 2, '.', '')
            ];
            $totalDiscount += $discountAmount;
        }
    }

    protected function applyBuy5Get1Discount(Order $order, &$discounts, &$totalDiscount, &$subtotal)
    {
        foreach ($order->orderItems as $item) {
            if ($item->product->category == 2 && $item->quantity >= 6) {
                $discountAmount = $item->unit_price;
                $subtotal -= $discountAmount;
                $discounts[] = [
                    'discountReason' => 'BUY_5_GET_1',
                    'discountAmount' => number_format($discountAmount, 2, '.', ''),
                    'subtotal'       => number_format($subtotal, 2, '.', '')
                ];
                $totalDiscount += $discountAmount;
            }
        }
    }

    protected function applyCheapestItemDiscount(Order $order, &$discounts, &$totalDiscount, &$subtotal)
    {
        $category1Items = $order->orderItems->filter(function($item) {
            return $item->product->category == 1;
        });
        if ($category1Items->sum('quantity') >= 2) {
            $cheapestItem = $category1Items->sortBy('unit_price')->first();
            if ($cheapestItem) {
                $discountAmount = round($cheapestItem->unit_price * 0.20, 2);
                $subtotal -= $discountAmount;
                $discounts[] = [
                    'discountReason' => 'CHEAPEST_20_PERCENT_OFF',
                    'discountAmount' => number_format($discountAmount, 2, '.', ''),
                    'subtotal'       => number_format($subtotal, 2, '.', '')
                ];
                $totalDiscount += $discountAmount;
            }
        }
    }
}