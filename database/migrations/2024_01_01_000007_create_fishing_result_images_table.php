<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fishing_result_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fishing_result_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('釣果ID');
            $table->string('path', 500)->comment('ストレージパス');
            $table->tinyInteger('sort_order')->default(0)->comment('表示順');
            $table->timestamps();

            $table->index(['fishing_result_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fishing_result_images');
    }
};
