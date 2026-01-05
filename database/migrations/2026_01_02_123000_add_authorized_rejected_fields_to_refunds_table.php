<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthorizedRejectedFieldsToRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->date('refund_authorized_date')->nullable();
            $table->string('refund_image')->nullable();
            $table->text('refund_authorized_description')->nullable();
            $table->text('refund_rejected_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn('refund_authorized_date');
            $table->dropColumn('refund_image');
            $table->dropColumn('refund_authorized_description');
            $table->dropColumn('refund_rejected_reason');
        });
    }
}
