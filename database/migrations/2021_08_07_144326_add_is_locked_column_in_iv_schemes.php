<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLockedColumnInIvSchemes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('iv_schemes', function (Blueprint $table) {
            if (!Schema::hasColumn('iv_schemes', 'is_locked')) {
                $table->boolean('is_locked')->nullable();
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
        Schema::table('iv_schemes', function (Blueprint $table) {
            if (Schema::hasColumn('iv_schemes', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
        });
    }
}
