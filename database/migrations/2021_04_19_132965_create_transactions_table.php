<?php


use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tnx')->unique();
            $table->string('type');
            $table->bigInteger('user_id')->index();
            $table->bigInteger('account_to')->nullable();
            $table->bigInteger('account_from')->nullable();
            $table->string('calc');
            $table->double('amount')->default(0);
            $table->double('fees')->default(0);
            $table->string('currency');
            $table->double('total')->default(0);
            $table->double('tnx_amount')->default(0);
            $table->double('tnx_fees')->default(0);
            $table->double('tnx_total')->default(0);
            $table->string('tnx_currency');
            $table->string('tnx_method')->nullable();
            $table->double('exchange')->default(0);
            $table->string('status');
            $table->bigInteger('refund')->nullable()->default(0);
            $table->string('pay_from')->nullable();
            $table->string('pay_to')->nullable();
            $table->string('reference')->nullable();
            $table->text('description');
            $table->text('remarks')->nullable();
            $table->text('note')->nullable();
            $this->jsonColumn($table,'meta');
            $table->dateTime('confirmed_at')->nullable();
            $this->jsonColumn($table,'confirmed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $this->jsonColumn($table,'completed_by')->nullable();
            $table->bigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
