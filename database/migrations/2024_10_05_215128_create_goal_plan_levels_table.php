<?php

use App\Models\Goal;
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
        Schema::create('goal_plan_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Goal::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(PlanLevel::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_plan_levels');
    }
};
