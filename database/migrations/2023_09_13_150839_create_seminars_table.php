<?php

use Carbon\Carbon;
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
        Schema::create('TT_SEMINAR', function (Blueprint $table) {
            $table->integer('SEMINAR_ID')->autoIncrement();
            $table->string('SEMINAR_TITLE', 200)->nullable(true);
            $table->boolean('IS_HALL_SEMINAR')->nullable(false);
            $table->boolean('IS_ONLINE_SEMINAR')->nullable(false);
            $table->dateTime('EVENT_STARTDATE')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('EVENT_ENDDATE')->nullable(true)->default(Carbon::now()->addDays(10)->day(10));
            $table->dateTime('PUBLICATION_START_DATE_TIME')->nullable(false)->default(now());
            $table->dateTime('PUBLICATION_END_DATE_TIME')->nullable(true)->default(Carbon::now()->addDays(10)->day(10));

            $table->string('LIST_OVERVIEW', 1000)->nullable(false)->default("このセミナーは、製薬に関する広範な知識と情報を提供することを目的としています。製薬業界は、医療の進歩と患者の健康向上に重要な役割を果たしています。このセミナーでは、製薬に関する最新の動向、技術、研究成果についての洞察を提供します。");
            $table->integer('SEMINAR_MAXIMUM_PARTICIPANT')->nullable(true);
            $table->string('ONLINE_VIEW_URL', 200)->nullable(true);
            $table->integer('QUESTIONNAIRE_ID')->nullable(true);
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
        Schema::dropIfExists('seminars');
    }
};
