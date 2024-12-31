<?php

use App\Models\GoalPlan;
use App\Models\User;
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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(GoalPlan::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('calories')->nullable();
            $table->boolean('active')->default(false);
            $table->integer('check')->default(0);
            $table->string('time')->nullable();
            $table->string('water')->nullable();
            $table->string('sleep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
