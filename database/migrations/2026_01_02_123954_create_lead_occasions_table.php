<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadOccasionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_occasions', function (Blueprint $table) {
            $table->increments('leadoccasions_id');
            $table->string('leadoccasions_title');
            $table->integer('leadoccasions_creatorid')->nullable();
            $table->timestamp('leadoccasions_created')->nullable();
            $table->timestamp('leadoccasions_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads_occasions');
    }
}
