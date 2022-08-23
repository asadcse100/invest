<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchemeColumnInIvInvest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('iv_invests', function (Blueprint $table) {
            if (!Schema::hasColumn('iv_invests', 'scheme_id')) {
                $table->unsignedBigInteger('scheme_id')->nullable();
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
        Schema::table('iv_invests', function (Blueprint $table) {
            if (Schema::hasColumn('iv_invests', 'scheme_id')) {
                $table->dropColumn('scheme_id');
            }
        });
    }
}
