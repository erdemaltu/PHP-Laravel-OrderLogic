<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;
    protected $customerRepository;

    public function __construct(
        OrderRepository $orderRepository, 
        ProductRepository $productRepository, 
        CustomerRepository $customerRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
    }

    public function listOrders()
    {
        return $this->orderRepository->getAllOrders();
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Müşteri kontrolü
            $customer = $this->customerRepository->find($data['customerId']);
            if (!$customer) {
                return ApiResponse::error("Geçersiz müşteri ID", 400);
            }

            $orderTotal = 0;
            $orderItems = [];

            foreach ($data['items'] as $item) {
                $product = $this->productRepository->find($item['productId']);

                if (!$product) {
                    return ApiResponse::error("Ürün ID {$item['productId']} bulunamadı.", 400);
                }

                if ($product->stock < $item['quantity']) {
                    return ApiResponse::error("Ürün {$product->name} için yeterli stok bulunmuyor.", 400);
                }

                $itemTotal = $item['unitPrice'] * $item['quantity'];
                $orderTotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unitPrice'],
                    'total'      => $itemTotal,
                ];
            }

            // Sipariş oluştur
            $order = $this->orderRepository->create([
                'customer_id' => $customer->id,
                'total'       => $orderTotal,
            ]);

            foreach ($orderItems as $item) {
                $this->orderRepository->addOrderItem($order, $item);
                $this->productRepository->decreaseStock($item['product_id'], $item['quantity']);
            }

            return ApiResponse::success("Sipariş başarıyla oluşturuldu.", $order->load('orderItems.product'));
        });
    }

    public function deleteOrder($order)
    {
        $this->orderRepository->delete($order);
        return ApiResponse::success("Sipariş başarıyla silindi.");
    }
}
