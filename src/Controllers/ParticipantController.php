<?php
namespace App\Controllers;

use PDO;
use Exception;

class ParticipantController {
    private $pdo;

    public function getParticipantsByEventId($eventId, $limit = 10, $offset = 0) {
        $sql = "SELECT p.*, c.certificate_id, c.file_path 
                FROM participants p 
                LEFT JOIN certificates c ON p.participant_id = c.participant_id 
                WHERE p.event_id = :event_id 
                ORDER BY p.registered_at ASC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countParticipantsByEventId($eventId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM participants WHERE event_id = ?");
        $stmt->execute([$eventId]);
        return (int)$stmt->fetchColumn();
    }

    public function getStatsByEventId($eventId) {
        $stats = [];
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM participants WHERE event_id = ?");
        $stmt->execute([$eventId]);
        $stats['total'] = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM participants WHERE event_id = ? AND status_kehadiran = 1");
        $stmt->execute([$eventId]);
        $stats['present'] = (int)$stmt->fetchColumn();

        $stats['absent'] = $stats['total'] - $stats['present'];
        return $stats;
    }

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registerForEvent($eventId, $data) {
        $errors = [];
        $nama = trim($data['nama_lengkap'] ?? '');
        $email = trim($data['email'] ?? '');
        $kategori = $data['kategori_peserta'] ?? 'Mahasiswa';
        
        if (empty($nama)) $errors[] = "Nama lengkap wajib diisi.";
        if (empty($email)) $errors[] = "Email wajib diisi.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid.";

        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        try {
            // Cek apakah sudah terdaftar di event ini
            $stmt = $this->pdo->prepare("SELECT participant_id FROM participants WHERE event_id = ? AND email = ?");
            $stmt->execute([$eventId, $email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'errors' => ["Anda sudah terdaftar di event ini menggunakan email tersebut."]];
            }

            $nim = $data['nim'] ?? null;
            $univ = $data['universitas'] ?? null;
            $instansi = $data['instansi'] ?? null;
            $telp = $data['nomor_telepon'] ?? null;

            $sql = "INSERT INTO participants (event_id, nama_lengkap, email, kategori_peserta, nim, universitas, instansi, nomor_telepon) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$eventId, $nama, $email, $kategori, $nim, $univ, $instansi, $telp]);

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Participant Reg Error: " . $e->getMessage());
            return ['success' => false, 'errors' => ["Terjadi kesalahan saat mendaftar."]];
        }
    }

    public function updateAttendance($userId, $participantId, $status) {
        try {
            // Verifikasi kepemilikan event melalui partisipan
            $sql = "SELECT p.participant_id FROM participants p 
                    JOIN events e ON p.event_id = e.event_id 
                    WHERE p.participant_id = ? AND e.user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$participantId, $userId]);
            
            if ($stmt->fetch()) {
                $stmt = $this->pdo->prepare("UPDATE participants SET status_kehadiran = ? WHERE participant_id = ?");
                $stmt->execute([(int)$status, $participantId]);
                return ['success' => true];
            }
            return ['success' => false, 'errors' => ["Akses ditolak."]];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }

    public function delete($userId, $participantId) {
        try {
            // Verifikasi kepemilikan
            $sql = "SELECT p.event_id FROM participants p 
                    JOIN events e ON p.event_id = e.event_id 
                    WHERE p.participant_id = ? AND e.user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$participantId, $userId]);
            $res = $stmt->fetch();

            if ($res) {
                $eventId = $res['event_id'];
                $stmt = $this->pdo->prepare("DELETE FROM participants WHERE participant_id = ?");
                $stmt->execute([$participantId]);
                return ['success' => true, 'event_id' => $eventId];
            }
            return ['success' => false, 'errors' => ["Peserta tidak ditemukan."]];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }
}
