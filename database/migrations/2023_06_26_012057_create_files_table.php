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
            $table->string('xid', 50)->unique(); // Adiciona o campo xid único

            // Definindo as chaves estrangeiras corretamente
            $table->string('user_xid', 50)->nullable();
            $table->string('client_xid', 50)->nullable();
            $table->string('system_xid', 50)->nullable();
            $table->string('type_xid', 50)->nullable();
            $table->string('sector_xid', 50)->nullable();

            // Relacionamentos com as tabelas
            $table->foreign('user_xid')->references('xid')->on('users')->onDelete('set null');
            $table->foreign('client_xid')->references('xid')->on('clients')->onDelete('set null');
            $table->foreign('system_xid')->references('xid')->on('systems')->onDelete('set null');
            $table->foreign('type_xid')->references('xid')->on('types')->onDelete('set null');
            $table->foreign('sector_xid')->references('xid')->on('sectors')->onDelete('set null');

            // Outras colunas
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
