<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('reference_no');
            $t->json('title');
            $t->json('description')->nullable();
            $t->string('doc_path')->nullable();
            $t->date('opens_at')->nullable();
            $t->dateTime('closes_at');
            $t->enum('status', ['draft', 'open', 'closed', 'awarded'])->default('open');
            $t->decimal('estimated_value_rm', 14, 2)->nullable();
            $t->timestamps();
            $t->index(['status', 'closes_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
