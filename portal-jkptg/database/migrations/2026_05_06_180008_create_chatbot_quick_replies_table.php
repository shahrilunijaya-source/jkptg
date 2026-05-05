<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatbot_quick_replies', function (Blueprint $t) {
            $t->id();
            $t->json('label');
            $t->string('payload_query');
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->index(['active', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_quick_replies');
    }
};
