<?php


use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('desc');
            $table->string('min_amount')->nullable();
            $table->string('max_amount')->nullable();
            $this->jsonColumn($table, 'config');
            $this->jsonColumn($table, 'fees')->nullable();
            $this->jsonColumn($table, 'currencies');
            $this->jsonColumn($table, 'countries')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('payment_methods');
    }
}
