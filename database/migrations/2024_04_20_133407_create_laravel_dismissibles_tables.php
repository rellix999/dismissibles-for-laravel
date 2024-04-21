<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('dismissibles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name')->unique();
            $table->dateTime('active_from');
            $table->dateTime('active_until')->nullable();
            $table->timestamps();
        });

        Schema::create('dismissals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dismissible_id');
            $table->morphs('dismisser');
            $table->dateTime('dismissed_until')->nullable();
            $table->json('extra_data')->nullable();
            $table->timestamps();

            $table
                ->foreign('dismissible_id')
                ->references('id')
                ->on('dismissibles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(
                ['dismissible_id', 'dismisser_type', 'dismisser_id', 'dismissed_until'],
                'dismisser_dismissible_until_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dismissibles');
        Schema::dropIfExists('dismissals');
    }
};
