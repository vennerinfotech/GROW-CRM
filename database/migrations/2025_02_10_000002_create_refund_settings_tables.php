<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundSettingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Refund Statuses
        if (!Schema::hasTable('refund_statuses')) {
            Schema::create('refund_statuses', function (Blueprint $table) {
                $table->increments('refundstatus_id');
                $table->string('refundstatus_title');
                $table->integer('refundstatus_position')->default(0);
                $table->string('refundstatus_color')->default('default');
                $table->enum('refundstatus_system_default', ['yes', 'no'])->default('no');
                $table->dateTime('refundstatus_created')->nullable();
                $table->dateTime('refundstatus_updated')->nullable();
            });

            // Seed default statuses
            DB::table('refund_statuses')->insert([
                ['refundstatus_title' => 'Pending', 'refundstatus_position' => 1, 'refundstatus_color' => 'warning', 'refundstatus_system_default' => 'yes'],
                ['refundstatus_title' => 'Completed', 'refundstatus_position' => 2, 'refundstatus_color' => 'success', 'refundstatus_system_default' => 'yes'],
                ['refundstatus_title' => 'Rejected', 'refundstatus_position' => 3, 'refundstatus_color' => 'danger', 'refundstatus_system_default' => 'yes'],
            ]);
        }

        // Refund Payment Modes
        if (!Schema::hasTable('refund_payment_modes')) {
            Schema::create('refund_payment_modes', function (Blueprint $table) {
                $table->increments('refundpaymentmode_id');
                $table->string('refundpaymentmode_title');
                $table->enum('refundpaymentmode_system_default', ['yes', 'no'])->default('no');
                $table->dateTime('refundpaymentmode_created')->nullable();
                $table->dateTime('refundpaymentmode_updated')->nullable();
            });

            // Seed default payment modes
            DB::table('refund_payment_modes')->insert([
                ['refundpaymentmode_title' => 'Bank Transfer', 'refundpaymentmode_system_default' => 'yes'],
                ['refundpaymentmode_title' => 'Cash', 'refundpaymentmode_system_default' => 'yes'],
                ['refundpaymentmode_title' => 'Cheque', 'refundpaymentmode_system_default' => 'yes'],
                ['refundpaymentmode_title' => 'Online', 'refundpaymentmode_system_default' => 'yes'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_statuses');
        Schema::dropIfExists('refund_payment_modes');
    }
}
