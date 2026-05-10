<?php
namespace App\Controllers;

use PDO;
use Exception;

class EventController {
    private $pdo;

    public function getEventById($userId, $eventId) {
        if ($userId === null) {
            $stmt = $this->pdo->prepare("SELECT * FROM events WHERE event_id = ?");
            $stmt->execute([$eventId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM events WHERE event_id = ? AND user_id = ?");
            $stmt->execute([$eventId, $userId]);
        }
        return $stmt->fetch();
    }

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getEventsByUserId($userId, $limit = 10, $offset = 0) {
        $sql = "SELECT event_id, nama_event, tanggal_mulai, kategori_event, created_at
               FROM events
               WHERE user_id = :user_id
               ORDER BY created_at DESC
               LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countEventsByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM events WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public function getGlobalStats($userId) {
        $stats = [];
        
        // Total Events
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM events WHERE user_id = ?");
        $stmt->execute([$userId]);
        $stats['total_events'] = (int)$stmt->fetchColumn();

        // Total Participants
        $stmt = $this->pdo->prepare("SELECT COUNT(p.participant_id) FROM participants p 
                                   JOIN events e ON p.event_id = e.event_id 
                                   WHERE e.user_id = ?");
        $stmt->execute([$userId]);
        $stats['total_participants'] = (int)$stmt->fetchColumn();

        // Total Attendance
        $stmt = $this->pdo->prepare("SELECT COUNT(p.participant_id) FROM participants p 
                                   JOIN events e ON p.event_id = e.event_id 
                                   WHERE e.user_id = ? AND p.status_kehadiran = 1");
        $stmt->execute([$userId]);
        $stats['total_attendance'] = (int)$stmt->fetchColumn();

        return $stats;
    }

    public function create($userId, $data, $files) {
        $errors = [];
        $nama = trim($data['nama_event'] ?? '');
        $deskripsi = trim($data['deskripsi'] ?? '');
        $kategori = $data['kategori_event'] ?? '';
        $tanggal = $data['tanggal_mulai'] ?? '';

        if (empty($nama)) $errors[] = "Nama event wajib diisi.";
        if (empty($tanggal)) $errors[] = "Tanggal mulai wajib diisi.";

        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        try {
            $gambar = null;
            if (isset($files['gambar_event']) && $files['gambar_event']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/posters/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $gambar = handleUploadGambar($files['gambar_event'], $uploadDir);
            }

            $sql = "INSERT INTO events (user_id, nama_event, deskripsi, kategori_event, tanggal_mulai, gambar_event) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $nama, $deskripsi, $kategori, $tanggal, $gambar]);

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Create Event Error: " . $e->getMessage());
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }

    public function update($userId, $eventId, $data, $files) {
        $errors = [];
        $nama = trim($data['nama_event'] ?? '');
        $deskripsi = trim($data['deskripsi'] ?? '');
        $kategori = $data['kategori_event'] ?? '';
        $tanggal = $data['tanggal_mulai'] ?? '';

        if (empty($nama)) $errors[] = "Nama event wajib diisi.";
        if (empty($tanggal)) $errors[] = "Tanggal mulai wajib diisi.";

        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        try {
            // Cek kepemilikan dan ambil gambar lama
            $stmt = $this->pdo->prepare("SELECT gambar_event FROM events WHERE event_id = ? AND user_id = ?");
            $stmt->execute([$eventId, $userId]);
            $event = $stmt->fetch();
            if (!$event) return ['success' => false, 'errors' => ["Event tidak ditemukan."]];

            $gambar = $event['gambar_event'];
            if (isset($files['gambar_event']) && $files['gambar_event']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/posters/';
                $newGambar = handleUploadGambar($files['gambar_event'], $uploadDir);
                if ($newGambar) {
                    // Hapus gambar lama jika ada yang baru
                    if ($gambar && file_exists($uploadDir . $gambar)) unlink($uploadDir . $gambar);
                    $gambar = $newGambar;
                }
            }

            $sql = "UPDATE events SET nama_event = ?, deskripsi = ?, kategori_event = ?, tanggal_mulai = ?, gambar_event = ? 
                    WHERE event_id = ? AND user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nama, $deskripsi, $kategori, $tanggal, $gambar, $eventId, $userId]);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }

    public function delete($userId, $eventId) {
        try {
            // Cek kepemilikan
            $stmt = $this->pdo->prepare("SELECT gambar_event FROM events WHERE event_id = ? AND user_id = ?");
            $stmt->execute([$eventId, $userId]);
            $event = $stmt->fetch();

            if ($event) {
                // Hapus gambar jika ada
                if ($event['gambar_event']) {
                    $filePath = __DIR__ . '/../../public/uploads/posters/' . $event['gambar_event'];
                    if (file_exists($filePath)) unlink($filePath);
                }

                $stmt = $this->pdo->prepare("DELETE FROM events WHERE event_id = ?");
                $stmt->execute([$eventId]);
                return ['success' => true];
            }
            return ['success' => false, 'errors' => ["Event tidak ditemukan."]];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }
}
