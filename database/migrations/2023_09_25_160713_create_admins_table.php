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
        Schema::create('TT_EMPLOYEES', function (Blueprint $table) {
            $table->integer("EMPLOYEE_ID")->autoIncrement()->unique();
            $table->string("EMPLOYEE_MAIL_ADDRESS", 255)->unique()->nullable(false);
            $table->string("EMPLOYEE_PASSWORD", 64)->nullable(false);
            $table->string("EMPLOYEE_NAME", 60)->nullable(false);
            $table->tinyInteger("AUTHORITY")->nullable(false);
            $table->boolean("IS_FIRST_LOGIN")->nullable(false)->default(0);

            $table->boolean("IS_DELETE")->nullable(false)->default(false);
            $table->string("CREATE_FUNC_ID", 15)->default("N/A")->nullable(false);
            $table->integer("CREATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("CREATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->string("UPDATE_FUNC_ID", 15)->default("N/A")->nullable(false);
            $table->integer("UPDATE_PERSON_ID")->default(1)->nullable(false);
            $table->dateTime("UPDATE_DATE_TIME")->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TT_EMPLOYEES');
    }
};
