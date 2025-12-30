<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('assessments', function (Blueprint $table) {
        $table->integer('violations')->default(0)->after('percentage');
    });
}

public function down()
{
    Schema::table('assessments', function (Blueprint $table) {
        $table->dropColumn('violations');
    });
}

};

