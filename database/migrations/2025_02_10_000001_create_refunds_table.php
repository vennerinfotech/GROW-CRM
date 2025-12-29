<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('refunds')) {
            Schema::create('refunds', function (Blueprint $table) {
                $table->increments('refund_id');
                $table->string('refund_bill_no')->nullable();
                $table->decimal('refund_amount', 10, 2)->default(0.0);
                $table->text('refund_reason')->nullable();
                $table->string('refund_courier')->nullable();
                $table->string('refund_docket_no')->nullable();

                // Foreign keys will be added but not constrained heavily to allow soft deletion flexibility
                $table->integer('refund_payment_modeid')->nullable()->default(0);
                $table->integer('refund_statusid')->nullable()->default(0);

                $table->integer('refund_error_by_userid')->nullable();
                $table->integer('refund_sales_by_userid')->nullable();
                $table->integer('refund_creatorid')->default(0);

                $table->dateTime('refund_created')->nullable();
                $table->dateTime('refund_updated')->nullable();

                // Indexes for performance
                $table->index('refund_payment_modeid');
                $table->index('refund_statusid');
                $table->index('refund_creatorid');
                $table->index('refund_bill_no');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
