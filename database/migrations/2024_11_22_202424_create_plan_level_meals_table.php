<?php

use App\Models\Meal;
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
        Schema::create('plan_level_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlanLevel::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(Meal::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('day');
            $table->integer('week');
            $table->boolean('breakfast');
            $table->boolean('lunch');
            $table->boolean('dinner');
            $table->boolean('snacks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_level_meals');
    }
};
