CREATE TABLE `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_organisasi` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `events` (
  `event_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `nama_event` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT,
  `tanggal_mulai` DATETIME NOT NULL,
  `kategori_event` ENUM('Kampus', 'Umum') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
);

CREATE TABLE `participants` (
  `participant_id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `nama_lengkap` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `kategori_peserta` ENUM('Mahasiswa', 'Umum') NOT NULL,
  `nim` VARCHAR(50) NULL,
  `universitas` VARCHAR(255) NULL,
  `instansi` VARCHAR(255) NULL,
  `nomor_telepon` VARCHAR(20) NULL,
  `status_kehadiran` BOOLEAN DEFAULT FALSE,
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`event_id`) ON DELETE CASCADE
);

CREATE TABLE `certificates` (
  `certificate_id` INT AUTO_INCREMENT PRIMARY KEY,
  `participant_id` INT NOT NULL,
  `unique_code` VARCHAR(100) NOT NULL UNIQUE,
  `file_path` VARCHAR(255) NOT NULL,
  `generated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`participant_id`) REFERENCES `participants`(`participant_id`) ON DELETE CASCADE
);