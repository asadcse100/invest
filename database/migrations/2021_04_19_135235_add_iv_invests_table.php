<?php

use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIvInvestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iv_invests', function (Blueprint $table) {
            $table->id();
            $table->string('ivx');
            $table->bigInteger("user_id");
            $table->double("amount");
            $table->double("profit");
            $table->double("total");
            $table->double("received");
            $table->string("currency");
            $table->string("rate");
            $table->string("term");
            $table->integer("term_count");
            $table->integer("term_total");
            $table->string("term_calc");
            $table->dateTime("term_start")->nullable();
            $table->dateTime("term_end")->nullable();
            $table->bigInteger("reference");
            $this->jsonColumn($table, "scheme");
            $table->unsignedBigInteger('scheme_id')->nullable();
            $this->jsonColumn($table, "meta")->nullable();
            $table->text('desc')->nullable();
            $table->text('remarks')->nullable();
            $table->text('note')->nullable();
            $table->string("status");
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
        Schema::dropIfExists('iv_invests');
    }
}
