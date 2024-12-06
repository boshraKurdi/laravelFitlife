<?php

use App\Models\Location;
use App\Models\Section;
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
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Location::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('open');
            $table->string('close');
            $table->string('type');
            $table->string('name');
            $table->string('price');
            $table->text('description');
            $table->text('description_ar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
