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
        Schema::create('clubcards', function (Blueprint $table) {
            $table->integer('number')->unique()->primary();
            $table->string('email');
            $table->string('password');
            $table->foreignIdFor(\App\Models\User::class)->nullable();
            $table->text('name')->nullable();
            $table->integer('points')->default(0);
            $table->text('auth_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubcards');
    }

};
