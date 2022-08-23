<?php


use App\Enums\EmailTemplateStatus;
use App\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subject')->nullable();
            $table->string('greeting')->nullable();
            $table->longText('content')->nullable();
            $table->string('group');
            $table->string('recipient')->nullable();
            $this->jsonColumn($table,'addresses')->nullable();
            $this->jsonColumn($table,'params')->nullable();
            $table->text('shortcut')->nullable();
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
        Schema::dropIfExists('email_templates');
    }
}
