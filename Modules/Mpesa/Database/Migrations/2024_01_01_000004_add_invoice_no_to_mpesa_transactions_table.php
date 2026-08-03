<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceNoToMpesaTransactionsTable extends Migration
{
    /**
     * Adds a dedicated invoice_no column to mpesa_transactions, so the
     * "M-Pesa > Transactions" admin page can show which Ultimate POS invoice
     * each payment belongs to without having to join out to the transactions
     * table every time (and so it still shows something even if the linked
     * sale is later voided/deleted).
     *
     * This gets populated automatically by the MpesaTransactionLinker
     * listener (see MpesaServiceProvider::boot()) the moment a matching
     * M-Pesa payment is saved against a sale - no manual step needed.
     */
    public function up()
    {
        if (!Schema::hasColumn('mpesa_transactions', 'invoice_no')) {
            Schema::table('mpesa_transactions', function (Blueprint $table) {
                $table->string('invoice_no')->nullable()->after('transaction_id')->index();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('mpesa_transactions', 'invoice_no')) {
            Schema::table('mpesa_transactions', function (Blueprint $table) {
                $table->dropColumn('invoice_no');
            });
        }
    }
}
