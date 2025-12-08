<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('mpesa_checkout_id')->nullable()->after('transaction_id');
            $table->string('mpesa_merchant_id')->nullable()->after('mpesa_checkout_id');
            $table->string('mpesa_result_code')->nullable()->after('mpesa_merchant_id');
            $table->text('mpesa_result_desc')->nullable()->after('mpesa_result_code');
            $table->string('phone_number')->nullable()->after('mpesa_result_desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'mpesa_checkout_id',
                'mpesa_merchant_id',
                'mpesa_result_code',
                'mpesa_result_desc',
                'phone_number',
            ]);
        });
    }
};
