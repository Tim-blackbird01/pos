<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etims_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->unique();
            $table->string('status', 20)->default('pending');
            $table->string('trader_invoice_number', 50);
            $table->unsignedBigInteger('etims_invoice_number')->nullable();
            $table->unsignedBigInteger('current_receipt_number')->nullable();
            $table->unsignedBigInteger('total_receipt_number')->nullable();
            $table->string('internal_data', 64)->nullable();
            $table->string('receipt_signature', 64)->nullable();
            $table->string('sdc_datetime', 14)->nullable();
            $table->text('error_message')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('etims_invoices');
    }
};
