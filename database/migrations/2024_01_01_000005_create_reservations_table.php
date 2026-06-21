<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('予約会員');
            $table->date('reserved_date')->comment('予約日');
            $table->enum('trip_type', ['morning', 'afternoon', 'night'])->comment('便');
            $table->tinyInteger('num_people')->unsigned()->default(1)->comment('人数');
            $table->text('remarks')->nullable()->comment('備考');
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
                'completed',
            ])->default('pending')->comment('予約ステータス');

            // 承認・却下
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('承認・却下者');
            $table->timestamp('approved_at')->nullable()->comment('承認・却下日時');
            $table->text('reject_reason')->nullable()->comment('却下理由');

            // キャンセル
            $table->enum('cancelled_by', ['member', 'master'])->nullable()->comment('キャンセル区分');
            $table->text('cancel_reason')->nullable()->comment('キャンセル理由');
            $table->timestamp('cancelled_at')->nullable()->comment('キャンセル日時');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['reserved_date', 'trip_type', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
