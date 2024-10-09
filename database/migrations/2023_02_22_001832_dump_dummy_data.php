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
        DB::statement("INSERT INTO merchants(name, user_pic,is_pkp, status) VALUES
            ('PT Kalo Berani',3,'Y',1)
        ");

        DB::statement("INSERT INTO merchant_addresses(id,address_name,full_address,province_id,city_id,district_id,subdistrict_id,postcode,is_default,merchant_id) VALUES
            (1,'Alamat Kantor','Jl Sidoarjo RT 99 RW 99 Blok A 99 NO 99', 35, 3515, 3515120,3515120006, '9999', 1, 1)
        ");

        DB::statement("INSERT INTO customer_addresses(id,address_name,full_address,shipping_province_id,shipping_city_id,shipping_district_id,shipping_subdistrict_id,shipping_postcode,is_default,user_id) VALUES
            (1,'Alamat Perusahaan','Jl Sidoarjo RT 99 RW 99 Blok A 99 NO 99', 35, 3515, 3515120,3515120006, '9999', 1, 2)
        ");

        DB::statement("INSERT INTO status(name) VALUES
            ('Menunggu Konfirmasi Penjual'),
            ('Menunggu Konfirmasi Pembeli'),
            ('Negosiasi'),
            ('Quotation Dibuat'),
            ('PO Dibuat'),
            ('DO Dibuat'),
            ('Pesanan Diproses'),
            ('Dalam Pengiriman'),
            ('Terkirim'),
            ('Selesai'),
            ('Komplain'),
            ('Kadaluwarsa'),
            ('Ditolak Pembeli'),
            ('Ditolak Penjual'),
            ('Menunggu Pembayaran')
        ");

        // DB::statement("INSERT INTO unit_types(name) VALUES ('Pcs'), ('Rim') ");

        // DB::statement('INSERT INTO categories(name,slug,parent_id,depth_level,icon,status) VALUES
        //     ("Elektronik","elektronik","",1,"",1), 
        //     ("Air Conditioner","ac",1,2,"",1)
        // ');

        // DB::statement("INSERT INTO lpse_account(user_id,username,role,id_instansi,nama_instansi,id_satker,nama_satker) VALUES
        //     (2,'LKPP1','DUMMY1','1','Lembaga Keperluan Penyedia Pemerintahan',1,'Divisi Pengadaan') 
        // ");

        // DB::statement("INSERT INTO carts(user_id,product_sku_id,qty,base_price,subtotal,tax_amount,total_price) 
        // VALUES(2,1,1,2500000,2500000,250000,2750000) ");
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
