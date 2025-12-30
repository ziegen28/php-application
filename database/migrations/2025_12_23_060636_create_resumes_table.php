<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {

            $table->id();

            // 🔒 ONE resume per user (CRITICAL FIX)
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->unique();

            $table->string('file_path');

            // Optional – filled after parsing
            $table->longText('extracted_text')->nullable();

            // Optional – updated after validation
            $table->enum('status', ['valid', 'invalid'])
                  ->nullable()
                  ->default(null);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
