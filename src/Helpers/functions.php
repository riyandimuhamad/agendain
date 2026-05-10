<?php

/**
 * Fungsi untuk menangani upload gambar event.
 */
function handleUploadGambar($fileInput, $uploadDir) {
    if (!isset($fileInput) || $fileInput['error'] !== UPLOAD_ERR_OK) {
        if ($fileInput['error'] == UPLOAD_ERR_NO_FILE) {
            return null;
        }
        throw new Exception("Error saat meng-upload file. Kode: " . $fileInput['error']);
    }

    $maxSize = 2 * 1024 * 1024;
    if ($fileInput['size'] > $maxSize) {
        throw new Exception("Ukuran file terlalu besar. Maksimal 2MB.");
    }

    $allowedTypes = [
        'image/jpeg' => '.jpg',
        'image/png'  => '.png',
        'image/jpg'  => '.jpg'
    ];
    $fileMimeType = mime_content_type($fileInput['tmp_name']);

    if (!isset($allowedTypes[$fileMimeType])) {
        throw new Exception("Tipe file tidak diizinkan. Hanya .jpg, .jpeg, atau .png.");
    }

    $fileExtension = $allowedTypes[$fileMimeType];
    $newFileName = 'event_' . time() . '_' . bin2hex(random_bytes(8)) . $fileExtension;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileInput['tmp_name'], $targetPath)) {
        return $newFileName;
    } else {
        throw new Exception("Gagal memindahkan file yang di-upload.");
    }
}

/**
 * Helper to get base URL
 */
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // Dapatkan path directory tempat script berjalan
    $scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    
    // Jika kita berada di dalam folder public, kita ingin base_url merujuk ke root project
    // agar pemanggilan base_url('public/...') tidak menduplikasi folder public.
    $projectRoot = str_replace('/public', '', $scriptPath);
    if ($projectRoot === '/') $projectRoot = '';
    
    return $protocol . "://" . $host . $projectRoot . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Simple view renderer
 */
function view($path, $data = []) {
    extract($data);
    require_once __DIR__ . '/../../views/' . $path . '.php';
}
