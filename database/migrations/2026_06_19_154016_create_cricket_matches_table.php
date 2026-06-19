<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cricket_matches', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('team1_id')->constrained('cricket_teams')->onDelete('cascade');
            $table->foreignId('team2_id')->constrained('cricket_teams')->onDelete('cascade');
            $table->string('match_status')->default('upcoming'); // upcoming, live, completed
            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);
            $table->integer('team1_wickets')->default(0);
            $table->integer('team2_wickets')->default(0);
            $table->decimal('overs_bowled', 4, 1)->default(0.0); // e.g., 18.4 overs
            $table->string('venue')->nullable();
            $table->string('match_result_details')->nullable(); // e.g., "Bangladesh won by 7 runs"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cricket_matches');
    }
};