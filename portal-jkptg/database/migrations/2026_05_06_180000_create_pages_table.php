<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->json('title');
            $t->json('body');
            $t->json('meta_title')->nullable();
            $t->json('meta_description')->nullable();
            $t->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('published')->default(true);
            $t->timestamps();
            $t->index(['published', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
