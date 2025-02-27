-- Membuat database
CREATE DATABASE latihan_fahmi;

-- Menggunakan database yang baru dibuat
USE latihan_fahmi;

-- Membuat tabel tb_jenis (tipe barang)
CREATE TABLE tb_jenis (
    jenis_id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_nama VARCHAR(100) NOT NULL,
    jenis_time DATETIME NOT NULL
);

-- Membuat tabel tb_barang
CREATE TABLE tb_barang (
    brg_id INT AUTO_INCREMENT PRIMARY KEY,
    brg_nama VARCHAR(100) NOT NULL,
    brg_harga DECIMAL(10, 2) NOT NULL,
    brg_stok INT NOT NULL,
    brg_jenis INT,
    brg_time DATETIME NOT NULL,
    FOREIGN KEY (brg_jenis) REFERENCES tb_jenis(jenis_id)
);

-- Membuat tabel tb_pelanggan
CREATE TABLE tb_pelanggan (
    pelanggan_id INT AUTO_INCREMENT PRIMARY KEY,
    pelanggan_nama VARCHAR(100) NOT NULL,
    pelanggan_alamat TEXT NOT NULL,
    pelanggan_telepon VARCHAR(20) NOT NULL,
    pelanggan_time DATETIME NOT NULL
);

-- Membuat tabel tb_transaksi
CREATE TABLE tb_transaksi (
    transaksi_id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_time DATETIME NOT NULL,
    transaksi_pelanggan INT NOT NULL,
    transaksi_barang INT NOT NULL,
    transaksi_jumlah INT NOT NULL,
    transaksi_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (transaksi_pelanggan) REFERENCES tb_pelanggan(pelanggan_id),
    FOREIGN KEY (transaksi_barang) REFERENCES tb_barang(brg_id)
);
