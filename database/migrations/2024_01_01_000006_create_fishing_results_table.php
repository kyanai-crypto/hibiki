<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fishing_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('投稿者');
            $table->date('result_date')->comment('釣果日');
            $table->string('fish_type', 100)->comment('魚種');
            $table->string('fish_size', 100)->nullable()->comment('サイズ');
            $table->text('comment')->nullable()->comment('コメント');
            $table->boolean('is_published')->default(true)->comment('公開フラグ');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'result_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fishing_results');
    }
};
