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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('book_id')->nullable();
            $table->string('author')->nullable();
            $table->string('description')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publish_date')->nullable();
            $table->string('edition')->nullable();
            $table->string('volume')->nullable();
            $table->string('pages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};