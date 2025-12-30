<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        /**
         * STEP 1: Clean invalid enum values safely
         */
        DB::statement("
            UPDATE resumes
            SET status = NULL
            WHERE status NOT IN ('valid', 'invalid')
               OR status = ''
        ");

        /**
         * STEP 2: Alter columns ONLY (no duplicate index)
         */
        Schema::table('resumes', function (Blueprint $table) {

            $table->enum('status', ['valid', 'invalid'])
                  ->nullable()
                  ->change();

            $table->longText('extracted_text')
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        // No rollback needed (data-safe migration)
    }
};
