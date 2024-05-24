<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('heartbeat_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('cpu_usage')->nullable();
            $table->integer('memory_used')->nullable();
            $table->integer('memory_free')->nullable();
            $table->integer('memory_total')->nullable();
            $table->integer('disk_used')->nullable();
            $table->integer('disk_free')->nullable();
            $table->integer('disk_total')->nullable();
            $table->string('git_branch')->nullable();
            $table->string('git_head')->nullable();
            $table->dateTime('git_head_modified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heartbeat_entries');
    }
};