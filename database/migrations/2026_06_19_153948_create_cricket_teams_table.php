<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cricket_teams', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('team_name')->unique();
            $table->string('short_name', 10); // e.g., BAN, IND, AUS
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cricket_teams');
    }
};