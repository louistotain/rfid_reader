<?php
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $rfid = $data['rfid'] ?? null;

    if ($rfid) {
        $stmt = $pdo->prepare("SELECT id FROM badges WHERE rfid = :rfid");
        $stmt->bindParam(':rfid', $rfid);
        $stmt->execute();
        $badge = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($badge) {
            $stmt = $pdo->prepare("INSERT INTO scans (badge_id, scan_date) VALUES (:badge_id, NOW())");
            $stmt->bindParam(':badge_id', $badge['id']);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Scan ajouté avec succès!", "badge_id" => $badge['id']]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'ajout du scan."]);
            }
        } else {
            echo json_encode(["error" => "Aucun badge trouvé pour cet RFID."]);
        }
    } else {
        echo json_encode(["error" => "RFID et type de scan sont requis."]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée. Utilisez POST."]);
}
?>
