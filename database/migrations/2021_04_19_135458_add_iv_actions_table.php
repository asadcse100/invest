<?php

use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIvActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iv_actions', function (Blueprint $table) {
            $table->id();
            $table->string("action");
            $table->bigInteger("action_by");
            $table->string("action_at");
            $table->string("type");
            $table->bigInteger("type_id")->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iv_actions');
    }
}
