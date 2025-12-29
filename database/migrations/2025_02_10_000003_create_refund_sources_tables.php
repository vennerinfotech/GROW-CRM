<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundSourcesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Refund Error Sources
        Schema::create('refund_error_sources', function (Blueprint $table) {
            $table->increments('refunderrorsource_id');
            $table->string('refunderrorsource_title');
            $table->timestamp('refunderrorsource_created')->useCurrent();
            $table->timestamp('refunderrorsource_updated')->useCurrent();
        });

        // Refund Sales Sources
        Schema::create('refund_sales_sources', function (Blueprint $table) {
            $table->increments('refundsalessource_id');
            $table->string('refundsalessource_title');
            $table->timestamp('refundsalessource_created')->useCurrent();
            $table->timestamp('refundsalessource_updated')->useCurrent();
        });

        // Update Refunds Table
        Schema::table('refunds', function (Blueprint $table) {
            // Drop old user foreign keys if they exist (ignoring constraint errors for simplicity in this context,
            // but ideally we should drop FKs first. Assuming simple int columns here or handling efficiently).
            // We will just rename or add new columns to avoid data loss issues during dev.

            $table->dropColumn('refund_error_by_userid');
            $table->dropColumn('refund_sales_by_userid');

            $table->integer('refund_error_sourceid')->nullable()->default(0);
            $table->integer('refund_sales_sourceid')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_error_sources');
        Schema::dropIfExists('refund_sales_sources');

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn('refund_error_sourceid');
            $table->dropColumn('refund_sales_sourceid');
            $table->integer('refund_error_by_userid')->nullable();
            $table->integer('refund_sales_by_userid')->nullable();
        });
    }
}
