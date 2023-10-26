<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('TT_SEMINAR_IMAGE', function (Blueprint $table) {
            $table->integer('SEMINAR_IMAGE_ID')->autoIncrement();
            $table->integer('SEMINAR_ID');
            $table->tinyInteger('IMAGE_CATEGORY')->nullable(false);
            $table->tinyInteger('DISPLAY_ORDER')->nullable(false);
            $table->string('FILE_NAME', 210)->nullable(false);
            $table->string('FILE_PATH', 255)->nullable(false);
            $table->boolean('IS_DELETE')->nullable(false)->default(0);

            $table->string("CREATE_FUNC_ID", 15)->default("M0502")->nullable(false);
            $table->integer("CREATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("CREATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->string("UPDATE_FUNC_ID", 15)->default("M0502")->nullable(false);
            $table->integer("UPDATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("UPDATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);

            $table->foreign('SEMINAR_ID')->references('SEMINAR_ID')->on('TT_SEMINAR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TT_SEMINAR_IMAGE');
    }
};
