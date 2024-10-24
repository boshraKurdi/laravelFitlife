<?php

use App\Models\Exercise;
use App\Models\PlanLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_level_exercises', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->string('week');
            $table->foreignIdFor(PlanLevel::class);
            $table->foreignIdFor(Exercise::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_level_exercises');
    }
};
