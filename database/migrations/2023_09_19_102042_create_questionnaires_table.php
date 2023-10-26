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
        Schema::create('TT_QUESTIONNAIRES', function (Blueprint $table) {

            $table->integer('QUESTIONNAIRE_ID')->autoIncrement();
            $table->tinyInteger('DISPLAY_CATEGORY')->nullable(false)->default(1);
            $table->integer('MEDICAL_CATEGORY_ID')->nullable(true);
            $table->boolean('IS_DELETE')->nullable(false)->default(0);

            $table->string("CREATE_FUNC_ID", 15)->default("M0502")->nullable(false);
            $table->integer("CREATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("CREATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->string("UPDATE_FUNC_ID", 15)->default("M0502")->nullable(false);
            $table->integer("UPDATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("UPDATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TT_QUESTIONNAIRES');
    }
};
