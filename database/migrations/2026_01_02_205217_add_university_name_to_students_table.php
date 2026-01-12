<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('students', function (Blueprint $table) {
        $table->string('university_name')->after('name');
        $table->dropColumn('university_id');
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->string('university_id')->nullable();
        $table->dropColumn('university_name');
    });
}

};
