<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 定休日マスタ
     * type=weekly  : 毎週◯曜日を定休（day_of_week を使用）
     * type=specific: 特定の日付を定休（date を使用）
     */
    public function up(): void
    {
        Schema::create('closed_days', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['weekly', 'specific'])->comment('定休パターン');
            $table->tinyInteger('day_of_week')->nullable()->comment('曜日 0=日〜6=土（weekly用）');
            $table->date('date')->nullable()->comment('特定日（specific用）');
            $table->string('reason', 255)->nullable()->comment('理由・備考');
            $table->boolean('is_active')->default(true)->comment('有効フラグ');
            $table->timestamps();

            $table->index(['type', 'is_active']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closed_days');
    }
};
