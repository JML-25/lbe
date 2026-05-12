<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps(); // created_at, updated_at
            $table->string('language', 20);
            $table->text('french');
            $table->text('translation');
            $table->text('note')->nullable();
            $table->string('reference', 60)->default('XXXXXXXX');
            $table->string('period', 8)->default('XXXXXXXX');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
