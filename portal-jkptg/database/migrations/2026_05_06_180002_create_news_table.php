<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->json('title');
            $t->json('excerpt')->nullable();
            $t->json('body');
            $t->string('banner_path')->nullable();
            $t->enum('type', ['berita', 'pengumuman'])->default('berita');
            $t->boolean('important')->default(false);
            $t->timestamp('published_at')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['type', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
