<?php

namespace App\Listeners;

use App\Events\SellCreatedOrModified;
use App\Services\EtimsService;

class SyncSaleToEtims
{
    public function handle(SellCreatedOrModified $event): void
    {
        $transaction = $event->transaction;

        if (! config('etims.enabled') || $transaction->type !== 'sell' || $transaction->status !== 'final') {
            return;
        }

        app(EtimsService::class)->submit($transaction);
    }
}
