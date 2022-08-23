<?php

use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AddIvSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iv_schemes', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug")->unique();
            $table->string("short")->nullable();
            $table->text("desc")->nullable();
            $table->double("amount");
            $table->double("maximum")->nullable();
            $table->boolean("is_fixed")->default(false);
            $table->integer("term");
            $table->string("term_type");
            $table->float("rate");
            $table->string("rate_type");
            $table->string('calc_period');
            $table->boolean("days_only");
            $table->boolean("capital");
            $table->string("payout");
            $table->string('status');
            $table->boolean('is_locked')->nullable();
            $table->boolean("featured");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iv_schemes');
    }
}
