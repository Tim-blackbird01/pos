<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesaTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // TESTING MODE: Drop and recreate table
        Schema::dropIfExists('mpesa_transactions');

        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('business_id');
            $table->integer('location_id')->nullable();

            // Link back to the Ultimate POS sale (transactions table)
            $table->integer('transaction_id')->nullable();

            // Type of mpesa flow
            $table->enum('type', ['stk', 'c2b'])->default('stk');

            // Payment status
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])
                ->default('pending');

            // Customer phone number
            $table->string('phone_number')->nullable();

            $table->decimal('amount', 22, 4)->nullable();

            // STK Push identifiers
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable()->index();

            // Payment completion identifiers
            $table->string('mpesa_receipt_number')->nullable()->index();
            $table->string('transaction_date')->nullable();

            // Callback result
            $table->string('result_code')->nullable();
            $table->text('result_desc')->nullable();

            // Full callback payload
            $table->longText('raw_callback')->nullable();

            // Customer reference
            $table->string('account_reference')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('mpesa_transactions');
    }
}