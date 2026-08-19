<?php

namespace App\Listeners;

use App\Events\SellCreatedOrModified;
use App\Services\EtimsService;

class SyncSaleToEtims
{
    public function handle(SellCreatedOrModified $event): void
    {
        $transaction = $event->transaction;

        if ($transaction->type !== 'sell' || $transaction->status !== 'final' || empty($transaction->business->etims_settings['enabled'])) {
            return;
        }

        app(EtimsService::class)->submit($transaction);
    }
}
