<?php


use App\Enums\Boolean;
use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('menu_name');
            $table->text('menu_link')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $this->jsonColumn($table,'seo')->nullable();
            $table->longText('content')->nullable();
            $table->string('lang')->default('en');
            $table->string('status');
            $table->unsignedBigInteger('pid')->default(0);
            $table->boolean('public')->default(1);
            $this->jsonColumn($table,'params')->nullable();
            $table->boolean('trash')->default(Boolean::YES);
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
        Schema::dropIfExists('pages');
    }
}
