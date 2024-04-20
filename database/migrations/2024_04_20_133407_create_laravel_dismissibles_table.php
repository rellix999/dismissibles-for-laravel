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
            $table->dateTime('active_start_date_time');
            $table->dateTime('active_end_date_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_dismissibles');
    }
};
