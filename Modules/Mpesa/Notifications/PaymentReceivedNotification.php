<?php

namespace Modules\Mpesa\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Mpesa\Entities\MpesaTransaction;

/**
 * In-app (bell icon) notification fired when an M-Pesa payment is confirmed.
 *
 * Uses Laravel's "database" channel - the exact same mechanism core
 * UltimatePOS uses for its own notifications (RecurringInvoiceNotification,
 * etc). It gets stored in the `notifications` table against whichever
 * App\User instance we ->notify() (see CallbackController::notifyPaymentReceived()),
 * and shows up automatically in the bell icon dropdown because:
 *
 *   1. App\User uses Notifiable, so $user->notify(...) just works.
 *   2. HomeController::loadMoreNotifications() / getTotalUnreadNotifications()
 *      already query auth()->user()->notifications() / unreadNotifications -
 *      no core change needed there.
 *   3. App\Utils\Util::parseNotifications() doesn't recognize this notification
 *      class natively, so it falls through to
 *      ModuleUtil::getModuleData('parse_notification', $notification), which
 *      calls Modules\Mpesa\Http\Controllers\DataController::parse_notification()
 *      (already implemented in this module) to render the bell text/icon/link.
 */
class PaymentReceivedNotification extends Notification
{
    use Queueable;

    /** @var MpesaTransaction */
    protected $mpesaTransaction;

    public function __construct(MpesaTransaction $mpesaTransaction)
    {
        $this->mpesaTransaction = $mpesaTransaction;
    }

    /**
     * Database-only - this is a bell-icon notification, not an email/SMS.
     * (If you want the cashier to also get an SMS/email/push the moment a
     * payment lands, add 'mail' and implement toMail() below.)
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Stored in the `notifications` table's `data` JSON column.
     * Read back by DataController::parse_notification() to build the bell text.
     */
    public function toArray($notifiable)
    {
        return [
            'mpesa_transaction_id' => $this->mpesaTransaction->id,
            'transaction_id' => $this->mpesaTransaction->transaction_id,
            'amount' => $this->mpesaTransaction->amount,
            'receipt' => $this->mpesaTransaction->mpesa_receipt_number,
            'phone_number' => $this->mpesaTransaction->phone_number,
        ];
    }
}
