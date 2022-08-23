<?php

use App\Enums\Boolean;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('label')->nullable();
            $table->string('short')->nullable();
            $table->longText('translations')->nullable();
            $table->enum('rtl', [Boolean::YES, Boolean::NO])->default(Boolean::NO);
            $table->enum('status', [Boolean::YES, Boolean::NO])->default(Boolean::YES);
            $table->timestamps();
        });

        DB::table('languages')->insert([
            'name' => 'English',
            'code' => 'en',
            'label' => 'English',
            'short' => 'ENG',
            'rtl' => Boolean::NO,
            'status' => Boolean::YES,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
