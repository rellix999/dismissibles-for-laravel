<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laravel_dismissibles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name')->unique();
            $table->dateTime('active_start');
            $table->dateTime('active_end');
            $table->timestamps();
        });

        Schema::create('laravel_dismissals', function (Blueprint $table) {
            $table->unsignedBigInteger('dismissible_id');
            $table->morphs('dismisser');
            $table->json('extra_data')->nullable();
            $table->timestamps();

            $table
                ->foreign('dismissible_id')
                ->references('id')
                ->on('laravel_dismissibles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_dismissibles');
        Schema::dropIfExists('laravel_dismissals');
    }
};
