<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->tinyInteger('role_refunds_dashboard')->default(0);
            $table->string('role_refunds_dashboard_scope')->default('global');
            $table->tinyInteger('role_refunds_initial')->default(0);
            $table->string('role_refunds_initial_scope')->default('global');
            $table->tinyInteger('role_refunds_authorized')->default(0);
            $table->string('role_refunds_authorized_scope')->default('global');
            $table->tinyInteger('role_refunds_completed')->default(0);
            $table->string('role_refunds_completed_scope')->default('global');
            $table->tinyInteger('role_refunds_rejected')->default(0);
            $table->string('role_refunds_rejected_scope')->default('global');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'role_refunds_dashboard',
                'role_refunds_dashboard_scope',
                'role_refunds_initial',
                'role_refunds_initial_scope',
                'role_refunds_authorized',
                'role_refunds_authorized_scope',
                'role_refunds_completed',
                'role_refunds_completed_scope',
                'role_refunds_rejected',
                'role_refunds_rejected_scope'
            ]);
        });
    }
};
