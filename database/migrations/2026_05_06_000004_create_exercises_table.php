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
        Schema::create('exercises', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->json('aliases')->nullable();
            $table->text('description')->nullable();
            $table->json('instructions')->nullable();
            $table->json('tips')->nullable();
            $table->string('difficulty')->nullable();
            $table->string('force')->nullable();
            $table->string('mechanic')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
