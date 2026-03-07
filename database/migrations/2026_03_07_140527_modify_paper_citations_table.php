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
        Schema::table('paper_citations', function (Blueprint $table) {
            // Check if indexes already exist before adding them (to stay robust)
            // But usually adding them is fine if they don't exist.
            // Using raw SQL to check for index might be overkill, let's just use try-catch or assume they aren't there.
            
            // Actually, let's just add the individual indexes.
            // Foreign keys MUST have an index.
            $table->index('published_paper_id');
            $table->index('user_id');
        });

        Schema::table('paper_citations', function (Blueprint $table) {
            // Drop unique constraint
            // We do this in a separate call to ensure indexes from above are committed in MySQL if possible
            $table->dropUnique(['published_paper_id', 'user_id']);
        });

        Schema::table('paper_citations', function (Blueprint $table) {
            // Add column if it doesn't exist
            if (!Schema::hasColumn('paper_citations', 'citing_paper_title')) {
                $table->string('citing_paper_title')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paper_citations', function (Blueprint $table) {
            if (Schema::hasColumn('paper_citations', 'citing_paper_title')) {
                $table->dropColumn('citing_paper_title');
            }
            
            // Restore unique constraint
            // Note: This might fail if duplicates were added while it was removed
            $table->unique(['published_paper_id', 'user_id']);

            $table->dropIndex(['published_paper_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
