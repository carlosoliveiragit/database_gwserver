<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            // Foreign Keys que viram NULL se o registro relacionado for deletado
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('system_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('type_id')->nullable()->constrained('types')->onDelete('set null');
            $table->foreignId('sector_id')->nullable()->constrained()->onDelete('set null');

            $table->string('path');
            $table->string('file');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
