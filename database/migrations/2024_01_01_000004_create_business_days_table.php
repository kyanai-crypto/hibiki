<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 特定日の営業設定（定休日の例外上書き含む）
     * is_holiday=true  : この日は休船（定休でない日を臨時休業にする場合）
     * is_holiday=false : この日は営業（定休日でも出船する場合の上書き）
     */
    public function up(): void
    {
        Schema::create('business_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->comment('対象日');
            $table->boolean('is_holiday')->default(false)->comment('休船フラグ');
            $table->boolean('morning_open')->default(true)->comment('午前便 営業有無');
            $table->boolean('afternoon_open')->default(false)->comment('午後便 営業有無');
            $table->boolean('night_open')->default(false)->comment('夜便 営業有無');
            $table->tinyInteger('morning_capacity')->nullable()->comment('午前便 定員上書き');
            $table->tinyInteger('afternoon_capacity')->nullable()->comment('午後便 定員上書き');
            $table->tinyInteger('night_capacity')->nullable()->comment('夜便 定員上書き');
            $table->string('note', 255)->nullable()->comment('備考');
            $table->timestamps();

            $table->index(['date', 'is_holiday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_days');
    }
};
