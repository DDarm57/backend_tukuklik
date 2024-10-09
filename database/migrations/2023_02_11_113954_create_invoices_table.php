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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->string('order_number');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('billing_number')->nullable();
            $table->float('invoice_amount', 16, 2)->default(0);
            $table->enum('status', ['Belum Dibayar', 'Sudah Dibayar', 'Jatuh Tempo', 'Kadaluwarsa', 'Menunggu Konfirmasi Penjual']);
            $table->string('payment_type');
            $table->dateTime('invoice_date');
            $table->dateTime('due_date');
            $table->dateTime('paid_date')->nullable();
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
        //
    }
};
