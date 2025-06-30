
CREATE DATABASE IF NOT EXISTS sembalun;
USE sembalun;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  email VARCHAR(100),
  password VARCHAR(255)
);

CREATE TABLE wisata (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_wisata VARCHAR(100),
  deskripsi TEXT,
  gambar VARCHAR(255),
  kategori VARCHAR(50),
  tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO wisata (nama_wisata, deskripsi, gambar, kategori) VALUES
('Gunung Rinjani', 'Gunung tertinggi dan paling ikonik di NTB.', 'rinjani.jpg', 'Pegunungan'),
('Air Terjun Benang Stokel', 'Air terjun yang berada di kaki Rinjani.', 'benangstokel.jpg', 'Air Terjun'),
('Kebun Strawberry', 'Wisata petik buah strawberry langsung di kebun.', 'strawberry.jpg', 'Pertanian'),
('Kebun Jeruk', 'Nikmati buah jeruk segar dari kebun Sembalun.', 'jeruk.jpg', 'Pertanian');
