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
        Schema::create('TT_MEMBER', function (Blueprint $table) {
            // composite primary key (MEMBER_ID, MEMBER_MAIL_ADDRESS)
            $table->integer('MEMBER_ID')->autoIncrement()->unique();
            $table->string('MEMBER_MAIL_ADDRESS');
            $table->string("PASSWORD", 64)->nullable(false);

            $table->smallInteger("MEMBER_STATUS")->nullable(false)->default(1);
            $table->dateTime("REGISTER_DATE_TIME")->default(now());
            $table->string("MEDICALINSTITUTION_NAME", 200)->default("大学病院ABC");
            $table->string("MEDICALINSTITUTION_NAME_KANA", 300)->default("ダイガクビョウインABC");
            $table->string("MEMBER_LAST_NAME", 40)->nullable(false)->default("山田");
            $table->string("MEMBER_FIRST_NAME", 80)->nullable(false)->default("太郎");
            $table->string("MEMBER_LAST_NAME_KANA", 40)->nullable(false)->default("ヤマダ");
            $table->string("MEMBER_FIRST_NAME_KANA", 80)->nullable(false)->default("タロウ");

            $table->string("POSTAL_CD", 8)->nullable(false)->default("00000000");
            $table->integer("PREFECTURE_ID")->nullable(false)->default(1);
            $table->string("MUNICIPALITIES", 20)->nullable(false)->default("大阪");
            $table->string("ADDRESS", 100)->nullable(false)->default("大阪市");
            $table->string("BUILDING", 100)->default("5309");
            $table->string("TELEPHONE", 13)->nullable(false)->default("000-0000-0000");
            $table->string("FAX_NUMBER", 13)->nullable(true);
            $table->integer("MEDICALCATEGORY_ID")->default(null)->nullable();
            $table->integer("SECRET_QUESTION_ID")->nullable();
            $table->string("ANSWER_QUESTION", 100)->nullable();
            $table->string("DEPARTMENT", 100)->nullable();
            $table->integer("OCCUPATION_ID")->nullable();
            $table->integer("SERVICE_CATEGORY_ID")->nullable();
            $table->integer("OFFICER_ID")->nullable();
            $table->boolean("IS_DELETE")->nullable(false)->default(false);
            $table->boolean("IS_DELIVERY")->nullable(false)->default(true);
            $table->integer("PROXY_INPUT_USER_ID")->nullable();
            $table->boolean("IS_FIRST_LOGIN")->nullable(false)->default(false);
            $table->dateTime("LOCK_TIME")->nullable();
            $table->string("NEW_EMAIL_ADDRESS")->nullable();
            $table->string("AUTH_NUMBER", 6)->nullable();
            $table->dateTime("AUTH_NUMBER_LIMIT")->nullable();
            $table->string("WITHDRAWAL_REASON", 200)->nullable();
            $table->string("OTHER_OPINION", 1000)->nullable();
            $table->string("CREATE_FUNC_ID", 15)->default("U0203")->nullable(false);
            $table->integer("CREATE_PERSON_ID")->default(random_int(1, 1000))->nullable(false);
            $table->dateTime("CREATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->string("UPDATE_FUNC_ID", 15)->default("U0203")->nullable(false);
            $table->integer("UPDATE_PERSON_ID")->default(random_int(1, 1000))->nullable(false);
            $table->dateTime("UPDATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
        });

        Schema::table('TT_MEMBER', function (Blueprint $table) {
            $table->dropPrimary('MEMBER_ID');
            $table->primary(['MEMBER_ID', 'MEMBER_MAIL_ADDRESS']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TT_MEMBER');
    }
};
