<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private array $tables = ['test_dismisser_ones', 'test_dismisser_twos'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::create($table, function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
