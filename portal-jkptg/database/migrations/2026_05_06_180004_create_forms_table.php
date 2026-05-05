<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->json('name');
            $t->json('description')->nullable();
            $t->string('file_path');
            $t->string('category');
            $t->string('version')->default('1.0');
            $t->unsignedInteger('file_size_bytes')->default(0);
            $t->unsignedInteger('downloads_count')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->index(['category', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
