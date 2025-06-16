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
        Schema::create('published_papers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('title')->index();
            $table->text('mla')->nullable();
            $table->text('apa')->nullable();
            $table->text('chicago')->nullable();
            $table->text('harvard')->nullable();
            $table->text('vancouver')->nullable();
            $table->string('doi')->nullable()->unique();
            $table->timestamps();
            // Add foreign key with ON DELETE CASCADE
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('published_papers');
    }
};
