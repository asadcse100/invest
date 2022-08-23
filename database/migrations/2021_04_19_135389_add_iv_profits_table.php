<?php

use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIvProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iv_profits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->index();
            $table->bigInteger("invest_id");
            $table->double("amount");
            $table->double("capital");
            $table->double("invested");
            $table->string("currency");
            $table->double("rate");
            $table->string("type");
            $table->integer('term_no');
            $table->bigInteger("payout")->nullable();
            $table->dateTime('calc_at');
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
        Schema::dropIfExists('iv_profits');
    }
}
