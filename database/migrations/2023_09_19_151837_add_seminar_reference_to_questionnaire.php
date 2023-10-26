<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('TT_SEMINAR', function (Blueprint $table) {
            $table->foreign('QUESTIONNAIRE_ID')->references('QUESTIONNAIRE_ID')->on('TT_QUESTIONNAIRES');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('TT_SEMINAR', function (Blueprint $table) {
            //
        });
    }
};
