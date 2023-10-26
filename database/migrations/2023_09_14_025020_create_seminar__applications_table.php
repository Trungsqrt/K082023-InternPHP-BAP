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
        Schema::create('TT_SEMINAR_APPLICATION', function (Blueprint $table) {
            $table->integer('SEMINAR_APPLICATION_ID')->autoIncrement();
            $table->integer('SEMINAR_ID');
            $table->integer('MEMBER_ID');
            $table->integer('SEMINAR_APPLICATION_CATEGORY')->nullable(false);
            $table->integer('QUESTIONNAIRE_ANSWER_ID')->nullable(false);
            $table->boolean('IS_DELETE')->nullable(false)->default(0);

            $table->string("CREATE_FUNC_ID", 15)->default("U0704")->nullable(false);
            $table->integer("CREATE_PERSON_ID")->default(random_int(1, 1000))->nullable(false);
            $table->dateTime("CREATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->string("UPDATE_FUNC_ID", 15)->default("U0704")->nullable(false);
            $table->integer("UPDATE_PERSON_ID")->default(random_int(1, 1000))->nullable(false);
            $table->dateTime("UPDATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);

            $table->foreign('SEMINAR_ID')->references('SEMINAR_ID')->on('TT_SEMINAR');
            $table->foreign('MEMBER_ID')->references('MEMBER_ID')->on('TT_MEMBER');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar__applications');
    }
};
