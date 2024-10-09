<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpse_account', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('username')->nullable();
            $table->string('role')->nullable();
            $table->string('id_instansi')->nullable();
            $table->string('nama_instansi')->nullable();
            $table->string('id_satker')->nullable();
            $table->string('nama_satker')->nullable();
            $table->text('token')->nullable();
            $table->text('token_lpse')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lpse_account');
    }
};
