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
        Schema::create('claim_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Citer who's making claim
            $table->string('citer_paper_title'); // Citer's own paper title
            $table->text('paper_link'); // Link to citer's paper
            $table->string('pdf_document')->nullable(); // Uploaded PDF document path
            $table->foreignId('referenced_paper_id')->constrained('published_papers')->onDelete('cascade'); // Paper that was cited
            $table->string('reference_id')->nullable();// Reference ID provided by citer
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // Admin review notes
            $table->decimal('claim_amount', 10, 2)->default(95.00); // 100 - 5% commission = 95
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who reviewed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_requests');
    }
};
