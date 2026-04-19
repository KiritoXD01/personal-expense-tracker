<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->morphs('transactable');
            $table->string('currency', 3);
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->timestamp('transacted_at')->useCurrent();
            $table->index(['user_id', 'currency']);
            $table->index(['user_id', 'transacted_at']);
            $table->timestamps();
        });
    }
};
