<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository
{
    public function getAllOrders()
    {
        return Order::with('customer', 'orderItems.product')->get();
    }

    public function find($id)
    {
        return Order::find($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function addOrderItem($order, array $itemData)
    {
        return $order->orderItems()->create($itemData);
    }

    public function delete($order)
    {
        $order->delete();
    }
}
