<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('llm_api_logs', function (Blueprint $t) {
            $t->id();
            $t->string('driver');
            $t->string('model');
            $t->unsignedInteger('prompt_tokens')->default(0);
            $t->unsignedInteger('completion_tokens')->default(0);
            $t->decimal('cost_usd', 10, 6)->default(0);
            $t->decimal('cost_rm', 10, 4)->default(0);
            $t->unsignedInteger('latency_ms')->default(0);
            $t->string('status');
            $t->text('error_message')->nullable();
            $t->foreignId('chat_session_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamp('created_at')->useCurrent();
            $t->index(['driver', 'created_at']);
            $t->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_api_logs');
    }
};
