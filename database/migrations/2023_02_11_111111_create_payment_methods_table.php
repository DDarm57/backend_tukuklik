<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('payment_type');
            $table->string('payment_name');
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('logo')->nullable();
            $table->string('payment_service');
            $table->boolean('is_active')->default(0);
            $table->string('payment_image')->default(null);
            $table->timestamps();
        });

        DB::statement("INSERT INTO payment_methods(payment_type, payment_name, payment_service, is_active) VALUES
            ('Bank Transfer', 'Bank Transfer BCA', 'Manual',1),
            ('Bank Transfer', 'Bank Transfer Mandiri', 'Manual', 1) ,
            ('Virtual Account', 'Mandiri Virtual Account', 'Midtrans', 1), 
            ('Virtual Account', 'BCA Virtual Account', 'Midtrans', 1) ,
            ('Virtual Account', 'BNI Virtual Account', 'Midtrans', 1) ,
            ('Virtual Account', 'BRI Virtual Account', 'Midtrans', 1) 
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
