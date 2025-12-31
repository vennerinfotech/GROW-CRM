<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundReasonsCouriersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Refund Reasons
        if (!Schema::hasTable('refund_reasons')) {
            Schema::create('refund_reasons', function (Blueprint $table) {
                $table->increments('refundreason_id');
                $table->string('refundreason_title');
                $table->timestamp('refundreason_created')->useCurrent();
                $table->timestamp('refundreason_updated')->useCurrent();
            });
        }

        // Refund Couriers
        if (!Schema::hasTable('refund_couriers')) {
            Schema::create('refund_couriers', function (Blueprint $table) {
                $table->increments('refundcourier_id');
                $table->string('refundcourier_title');
                $table->timestamp('refundcourier_created')->useCurrent();
                $table->timestamp('refundcourier_updated')->useCurrent();
            });
        }

        // Update Refunds Table
        Schema::table('refunds', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('refunds', 'refund_reason')) {
                $table->dropColumn('refund_reason');
            }
            if (Schema::hasColumn('refunds', 'refund_courier')) {
                $table->dropColumn('refund_courier');
            }

            // Add new columns
            if (!Schema::hasColumn('refunds', 'refund_reasonid')) {
                $table->integer('refund_reasonid')->nullable()->default(0);
            }
            if (!Schema::hasColumn('refunds', 'refund_courierid')) {
                $table->integer('refund_courierid')->nullable()->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_reasons');
        Schema::dropIfExists('refund_couriers');

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn('refund_reasonid');
            $table->dropColumn('refund_courierid');
            $table->text('refund_reason')->nullable();
            $table->string('refund_courier')->nullable();
        });
    }
}
