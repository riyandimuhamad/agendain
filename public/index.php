<?php
require_once __DIR__ . '/../src/bootstrap.php';

$public_events = [];
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $sql = "SELECT event_id, nama_event, deskripsi, tanggal_mulai, gambar_event 
            FROM events 
            WHERE kategori_event = 'Umum' AND tanggal_mulai >= CURDATE()";

    if (!empty($search_keyword)) {
        $sql .= " AND (nama_event LIKE :keyword OR deskripsi LIKE :keyword)";
    }

    $sql .= " ORDER BY tanggal_mulai ASC LIMIT 9";

    $stmt = $pdo->prepare($sql);

    if (!empty($search_keyword)) {
        $param_keyword = "%" . $search_keyword . "%";
        $stmt->bindParam(':keyword', $param_keyword, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        $public_events = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Error DB: " . $e->getMessage());
}

// Render view
view('pages/home', [
    'public_events' => $public_events,
    'search_keyword' => $search_keyword
]);
