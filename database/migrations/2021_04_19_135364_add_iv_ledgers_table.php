<?php

use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIvLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iv_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('ivx');
            $table->bigInteger("user_id");
            $table->string('type');
            $table->string('calc');
            $table->double("amount");
            $table->double("fees");
            $table->double("total");
            $table->string("currency");
            $table->text('desc')->nullable();
            $table->text('remarks')->nullable();
            $table->text('note')->nullable();
            $table->bigInteger("invest_id");
            $table->bigInteger("tnx_id");
            $table->bigInteger('reference');
            $this->jsonColumn($table, "meta")->nullable();
            $table->string("source")->nullable();
            $table->string("dest")->nullable();
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
        Schema::dropIfExists('iv_ledgers');
    }
}
