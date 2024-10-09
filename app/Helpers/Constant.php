<?php 

namespace App\Helpers;

class Constant { 

    /* Transaction Description */
    CONST RFQ_CREATED = "Permintaan Berhasil Dibuat Dengan Nomor %s. Batas Waktu Konfirmasi Sebelum %s";
    CONST NEGOTIATION_APPROVED = "Negosiasi Telah Disetujui Penjual Dan Penawaran Baru Dengan Nomor %s Telah Terbuat";
    CONST RFQ_APPROVED = "Permintaan Telah Dikonfirmasi penjual";
    CONST QUOTATION_CREATED = "Penawaran Telah Dibuat Penjual Dengan Nomor %s. Pembeli Harap Konfirmasi Sebelum %s";
    CONST RFQ_REJECTED = "Permintaan telah ditolak oleh penjual dengan alasan %s";
    CONST QUOTATION_APPROVED = "Penawaran Telah Dikonfirmasi Pembeli. Pesanan Telah Dibuat Dengan Nomor %s";
    CONST QUOTATION_REJECTED = "Penawaran telah ditolak oleh pembeli dengan alasan %s";
    CONST NEGOTIATION_CREATED = "Pembeli Melakukan Negosiasi";
    CONST NEGOTIATION_REJECTED = "Penjual Menolak Negosiasi Dengan Alasan %s";
    CONST DELIVERY_ORDER_CREATED = "Pesanan Telah Dikirim Oleh Penjual Dengan No. Resi %s";
    CONST DELIVERY_ORDER_CONFIRMED = "Pesanan Telah Diterima Oleh %s Pada Tanggal %s";
    CONST PURCHASE_ORDER_CONFIRMED = "Penjual Telah Menyetujui Pesanan. %s";
    CONST PURCHASE_ORDER_CBD = "Pembeli Diharap Selesaikan Pembayaran Sebelum Proses Pengiriman.";
    CONST PURCHASE_ORDER_TOP = "Saat Ini Pesanan Akan Diproses Oleh Penjual Dengan Estimasi Sampai %s";
    CONST PURCHASE_ORDER_REJECTED = "Pesanan Dibatalkan Oleh Penjual Dengan Alasan %s";
    CONST INVOICE_CREATED = "Invoice Telah Terbit Pada Tanggal %s Dan Akan Jatuh Tempo Pada %s";
    CONST INVOICE_TOP_PAID = "Tagihan Telah Dibayar Pembeli. Pesanan Selesai";
    CONST INVOICE_CBD_PAID = "Tagihan Telah Dibayar Pembeli. Saat Ini Pesanan Akan Diproses Pengiriman Ke Alamat Pembeli";
    CONST INVOICE_HAS_EXPIRED = "Tagihan Telah Kadaluwarsa Karena Sudah Melewati Tanggal Jatuh Tempo";

    CONST NEW_CUSTOMER = "Customer Baru Dengan Nama Lengkap %s Telah Terdaftar";

    /* Echannel Mandiri */
    CONST ECHANNEL_NAME = "Tukuklik";
    CONST ECHANNEL_CREATED = "Tagihan Tukuklik";

    /* Payment Type */
    CONST CBD = "Cash Before Delivery";
    CONST TOP = "Term Of Payment";

    /* Notes For Tokodaring Confirmation Report */
    CONST NOTES_TOKODARING_ACCEPTED = "Transaksi Sudah Dibayar & Sudah Diterima Oleh Customer";
    CONST NOTES_TOKODARING_REJECTED = "Transaksi Telah Dibatalkan Oleh Customer";

    /* Invoice Status */
    CONST INVOICE_NOT_YET_PAID = "Belum Dibayar";
    CONST INVOICE_ALREADY_PAID = "Sudah Dibayar";
    CONST INVOICE_DUE_DATE = "Jatuh Tempo";
    CONST INVOICE_EXPIRED = "Kadaluwarsa";
    CONST INVOICE_WAITING_SELLER_CONFIRMED = "Menunggu Konfirmasi Penjual";

    CONST AUTO_REPLY = "Terima kasih telah mengirim pesan kepada kami. Secepatnya dari tim kami akan membalas pesan anda.";
}