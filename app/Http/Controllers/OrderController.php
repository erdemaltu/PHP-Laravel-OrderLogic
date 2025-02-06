<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Models\Order;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return $this->orderService->listOrders();
    }

    public function store(OrderRequest $request)
    {
        return $this->orderService->createOrder($request->validated());
    }

    public function destroy(Order $order)
    {
        return $this->orderService->deleteOrder($order);
    }
}
