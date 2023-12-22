<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PayoutOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Use the API service to send a payout of the correct amount.
     * Note: The order status must be paid if the payout is successful, or remain unpaid in the event of an exception.
     *
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        // TODO: Complete this method

        try {
            $this->order->update(['payout_status' => Order::STATUS_PAID]);

            $affiliate = $this->order->affiliate;
            $email = $affiliate->user->email;
            $commissionOwed = $this->order->commission_owed;

            $apiService->sendPayout($email, $commissionOwed);
        } catch (\RuntimeException $exception) {
            // Set back to unpaid on failed
            $this->order->update(['payout_status' => Order::STATUS_UNPAID]);
            throw $exception;
        }
    }
}
