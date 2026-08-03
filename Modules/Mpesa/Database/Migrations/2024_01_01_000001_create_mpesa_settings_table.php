<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mpesa_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Ultimate POS multi-business support: one settings row per business
            $table->integer('business_id')->unique();

            // Environment: sandbox or production
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');

            // Daraja app credentials (entered by the admin in the UI)
            $table->text('consumer_key')->nullable();
            $table->text('consumer_secret')->nullable();

            // Shortcode setup
            $table->string('shortcode')->nullable(); // PayBill or Till number
            $table->enum('shortcode_type', ['paybill', 'till'])->default('paybill');
            $table->text('passkey')->nullable(); // Used for STK Push (Lipa Na Mpesa Online)

            // Used for B2C / reversal / status query type calls (optional, for future use)
            $table->text('initiator_name')->nullable();
            $table->text('security_credential')->nullable();

            // Account reference shown to customer during STK push
            $table->string('account_reference')->nullable();
            $table->string('transaction_desc')->nullable()->default('Payment');

            // Whether module is active/enabled for this business
            $table->boolean('is_enabled')->default(false);

            // Whether to auto register C2B confirmation/validation URLs on save
            $table->boolean('c2b_registered')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('mpesa_settings');
    }
}
