<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->integer('total_questions')->nullable();
            $table->integer('correct_answers')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn([
                'total_questions',
                'correct_answers',
                'percentage'
            ]);
        });
    }
};
