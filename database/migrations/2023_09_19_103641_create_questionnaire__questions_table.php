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
        Schema::create('TT_QUESTIONNAIRES_QUESTION', function (Blueprint $table) {

            $table->integer('QUESTIONNAIRES_QUESTION_ID')->autoIncrement();
            $table->integer('QUESTIONNAIRE_ID');
            $table->boolean('IS_REQUIRED_ANSWER')->nullable(false);
            $table->integer('QUESTIONNAIRE_CDFORMAT')->nullable(false);
            $table->string('QUESTIONNAIRE_QUESTION', 100)->nullable(false);
            $table->integer('DISPLAY_ORDER')->nullable(false)->default(1);

            $table->boolean('IS_DELETE')->nullable(false)->default(0);

            $table->string("CREATE_FUNC_ID", 15)->default("M0502")->nullable(false);
            $table->integer("CREATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("CREATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->string("UPDATE_FUNC_ID", 15)->default("M0502")->nullable(false);
            $table->integer("UPDATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("UPDATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);

            $table->foreign('QUESTIONNAIRE_ID')->references('QUESTIONNAIRE_ID')->on('TT_QUESTIONNAIRES');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire__questions');
    }
};
