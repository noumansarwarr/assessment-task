<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
        $affiliate = Affiliate::whereHas('user', function ($query) use ($data) {
            $query->whereEmail($data['customer_email']);
        })->first();

        if (!$affiliate) {
            $merchant = Merchant::whereDomain($data['merchant_domain'])->first();

            $affiliate = $this->affiliateService->register(
                $merchant,
                $data['customer_email'],
                $data['customer_name'],
                $merchant->default_commission_rate
            );
        }

        $order = Order::find($data['order_id']);

        if ($order) {
            Order::create([
                'affiliate_id' => $affiliate->id,
                'merchant_id' => $merchant->id,
                'subtotal' => $data['subtotal_price'],
                'commission_owed' => $data['subtotal_price'] * $affiliate->commission_rate,
                'discount_code' => $data['discount_code'],
                'payout_status' => Order::STATUS_UNPAID
            ]);
        }
    }
}
