<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cricket_players', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign Key linking to cricket_teams table
            $table->foreignId('team_id')->constrained('cricket_teams')->onDelete('cascade');
            $table->string('player_name');
            $table->string('role'); // Batsman, Bowler, All-rounder
            $table->integer('total_runs')->default(0);
            $table->integer('total_wickets')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cricket_players');
    }
};