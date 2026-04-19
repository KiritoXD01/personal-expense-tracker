<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('bank_id')->references('id')->on('banks')->cascadeOnDelete();
            $table->string('name');
            $table->string('currency', 3);
            $table->string('brand', 25);
            $table->string('type', 10);
            $table->string('last_four_digits', 4);
            $table->timestamps();
        });
    }
};
