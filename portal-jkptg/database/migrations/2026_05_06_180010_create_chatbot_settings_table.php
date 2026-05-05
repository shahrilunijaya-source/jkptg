<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatbot_settings', function (Blueprint $t) {
            $t->id();
            $t->string('driver')->default('canned');
            $t->string('model')->default('claude-sonnet-4-6');
            $t->decimal('cost_month_to_date_rm', 8, 2)->default(0);
            $t->decimal('cost_cap_rm', 8, 2)->default(200);
            $t->unsignedTinyInteger('alert_threshold_pct')->default(80);
            $t->boolean('kill_switch_active')->default(false);
            $t->timestamp('cap_reset_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_settings');
    }
};
