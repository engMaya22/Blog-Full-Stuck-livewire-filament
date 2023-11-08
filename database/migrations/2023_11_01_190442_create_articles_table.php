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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->longText('content');
            $table->boolean('active')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();

            
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
