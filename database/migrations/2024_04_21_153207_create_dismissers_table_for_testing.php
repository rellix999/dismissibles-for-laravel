<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (App::environment() !== 'testing') {
            return;
        }

        Schema::create('dismissers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (App::environment() !== 'testing') {
            return;
        }

        Schema::dropIfExists('dismissers');
    }
};
