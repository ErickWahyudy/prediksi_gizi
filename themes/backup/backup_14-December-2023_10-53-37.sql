#
# TABLE STRUCTURE FOR: tb_balita
#

DROP TABLE IF EXISTS `tb_balita`;

CREATE TABLE `tb_balita` (
  `id_balita` varchar(15) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tempat_lahir` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `nama_ayah` varchar(40) NOT NULL,
  `nama_ibu` varchar(40) NOT NULL,
  `tinggi_bb` varchar(5) NOT NULL,
  `berat_bb` varchar(5) NOT NULL,
  PRIMARY KEY (`id_balita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`, `tinggi_bb`, `berat_bb`) VALUES ('B002397sSQ', 'Ani', 'Perempuan', '2021-09-03', 'Jalen', 'Jalen Balong', 'Bagus', 'Sri', '86', '11.8');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`, `tinggi_bb`, `berat_bb`) VALUES ('B003SXGR9U', 'Dwi', 'Perempuan', '2020-03-05', 'Ponorogo', 'Balong', 'Wahyu', 'Ndari', '95', '12');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`, `tinggi_bb`, `berat_bb`) VALUES ('B0045oIaz4', 'Doni', 'Laki-laki', '2021-12-11', 'Ponorogo', 'Balong', 'Warno', 'Paitun', '80', '11');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`, `tinggi_bb`, `berat_bb`) VALUES ('B005I1pruL', 'Bayu', 'Laki-laki', '2021-11-02', 'Balong', 'Balong', 'Mar', 'Tini', '84', '9.5');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`, `tinggi_bb`, `berat_bb`) VALUES ('B006Bnpi6l', 'Kevin', 'Laki-laki', '2021-06-04', 'Ponorogo', 'Balong', 'Yoso', 'Tin', '89', '12.6');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`, `tinggi_bb`, `berat_bb`) VALUES ('B007PTlyyX', 'Amelia Shintya Putri', 'Perempuan', '2021-12-14', 'Ponorgo', 'Jl. ddsygs Babadan', 'Agus', 'Bu Gus', '94', '9.6');


#
# TABLE STRUCTURE FOR: tb_level
#

DROP TABLE IF EXISTS `tb_level`;

CREATE TABLE `tb_level` (
  `id_level` varchar(2) NOT NULL,
  `level` varchar(15) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('1', 'superadmin');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('2', 'admin');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('3', 'dosen');


#
# TABLE STRUCTURE FOR: tb_pengaturan
#

DROP TABLE IF EXISTS `tb_pengaturan`;

CREATE TABLE `tb_pengaturan` (
  `id_pengaturan` varchar(7) NOT NULL,
  `nama_judul` varchar(50) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `background` text NOT NULL,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengaturan` (`id_pengaturan`, `nama_judul`, `meta_keywords`, `meta_description`, `background`) VALUES ('P1xhDwL', 'Status Gizi Balita', 'Kassandra Production, Prediksi Gizi, Gizi Anak, Gizi Balita', 'Sistem Prediksi Gizi Balita merupakan web aplikasi yang dirancang untuk mengimplementasikan sistem untuk mengindentifikasi status gizi balita menggunakan metode Fuzzy Tsukamoto', 'header_656f3421970de.jpg');


#
# TABLE STRUCTURE FOR: tb_pengguna
#

DROP TABLE IF EXISTS `tb_pengguna`;

CREATE TABLE `tb_pengguna` (
  `id_pengguna` varchar(15) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `keterangan` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `foto_profile` text NOT NULL,
  `id_level` varchar(2) NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  KEY `id_level` (`id_level`),
  CONSTRAINT `tb_pengguna_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `tb_level` (`id_level`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A001bnHDs', 'Erik W', '081456141227', 'Ini admin', 'erik@gmail.com', '202cb962ac59075b964b07152d234b70', 'profile_656f409c3b590.jpeg', '1');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A002hfv3Ec', 'Budi', '186361', 'Faskes', 'budi@gmail.com', '202cb962ac59075b964b07152d234b70', '', '2');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A0035j2ctA', 'Dika', '927762', 'Kepala Lab', 'dika@gmail.com', '202cb962ac59075b964b07152d234b70', '', '2');


