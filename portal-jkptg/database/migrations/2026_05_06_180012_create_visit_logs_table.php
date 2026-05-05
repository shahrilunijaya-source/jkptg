<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visit_logs', function (Blueprint $t) {
            $t->id();
            $t->string('page_path');
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent_hash', 64)->nullable();
            $t->string('country', 2)->nullable();
            $t->string('locale', 5)->nullable();
            $t->string('referer', 512)->nullable();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamp('created_at')->useCurrent();
            $t->index(['page_path', 'created_at']);
            $t->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};
