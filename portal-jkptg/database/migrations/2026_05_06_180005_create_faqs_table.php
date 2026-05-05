<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('category');
            $t->json('question');
            $t->json('answer');
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->index(['category', 'active', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
