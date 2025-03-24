<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('xid', 50)->unique(); // Adicionando o campo 'xid' único
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('sector_xid', 50)->nullable(); // Altere para 'sector_xid'
            $table->string('profile_xid', 50)->nullable(); // Altere para 'profile_xid'
            $table->boolean('admin_lte_dark_mode')->default(false);
            $table->timestamps();

            // Defina as chaves estrangeiras com 'xid'
            $table->foreign('sector_xid')->references('xid')->on('sectors')->onDelete('set null');
            $table->foreign('profile_xid')->references('xid')->on('profiles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
