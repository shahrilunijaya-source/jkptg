<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $t) {
            $t->id();
            $t->string('reference_number', 32)->unique();
            $t->string('name');
            $t->string('email');
            $t->string('phone', 32)->nullable();
            $t->string('category', 64);
            $t->string('subject');
            $t->text('message');
            $t->string('status', 24)->default('baru');
            $t->ipAddress('ip_address')->nullable();
            $t->timestamps();
            $t->index(['status', 'created_at']);
            $t->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
