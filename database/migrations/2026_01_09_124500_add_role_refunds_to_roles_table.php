<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleRefundsToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'role_refunds')) {
                $table->tinyInteger('role_refunds')->default(0)->after('role_expenses');
            }
            if (!Schema::hasColumn('roles', 'role_refunds_scope')) {
                $table->string('role_refunds_scope')->default('global')->after('role_refunds');
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
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('role_refunds');
            $table->dropColumn('role_refunds_scope');
        });
    }
}
