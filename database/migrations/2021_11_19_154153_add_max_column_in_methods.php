<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxColumnInMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'max_amount')) {
                $table->string('max_amount')->nullable();
            }
        });

        Schema::table('withdraw_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('withdraw_methods', 'max_amount')) {
                $table->string('max_amount')->nullable();
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
        Schema::table('payment_methods', function (Blueprint $table) {
            if (Schema::hasColumn('payment_methods', 'max_amount')) {
                $table->dropColumn('max_amount');
            }
        });

        Schema::table('withdraw_methods', function (Blueprint $table) {
            if (Schema::hasColumn('withdraw_methods', 'max_amount')) {
                $table->dropColumn('max_amount');
            }
        });
    }
}
