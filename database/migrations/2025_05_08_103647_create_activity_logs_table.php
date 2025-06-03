<?php

use App\Enums\ActivityLogType;
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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->noActionOnDelete();
            $table->string('action_type')->default(ActivityLogType::SEARCH);
            $table->string('registration_no')->nullable();;
            $table->json('search_parameters')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('action_type');
            $table->index(['action_type', 'created_at']);
            $table->index('registration_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
