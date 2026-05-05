<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatbot_knowledge', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->json('question');
            $t->json('answer');
            $t->json('keywords')->nullable();
            $t->string('source_url')->nullable();
            $t->string('category');
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->index(['category', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledge');
    }
};
