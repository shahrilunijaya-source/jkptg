<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->json('name');
            $t->json('summary');
            $t->json('eligibility')->nullable();
            $t->json('process_steps')->nullable();
            $t->json('required_documents')->nullable();
            $t->string('category')->nullable();
            $t->string('sop_path')->nullable();
            $t->string('carta_alir_path')->nullable();
            $t->unsignedInteger('processing_days')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->index(['active', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
