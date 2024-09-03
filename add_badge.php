<?php
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $rfid = $data['rfid'] ?? null;

    if ($rfid) {
        // Vérifier si le badge existe
        $stmt = $pdo->prepare("SELECT id FROM badges WHERE rfid = :rfid");
        $stmt->bindParam(':rfid', $rfid);
        $stmt->execute();
        $badge = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($badge) {
            // Récupérer la dernière action du badge
            $stmt = $pdo->prepare("SELECT action FROM scans WHERE badge_id = :badge_id ORDER BY scan_date DESC LIMIT 1");
            $stmt->bindParam(':badge_id', $badge['id']);
            $stmt->execute();
            $lastScan = $stmt->fetch(PDO::FETCH_ASSOC);

            // Déterminer la nouvelle action en fonction de la dernière action
            // Si aucune action précédente n'existe, l'action est 0
            $newAction = ($lastScan && $lastScan['action'] == 1) ? 0 : 1;
            if (!$lastScan) {
                $newAction = 0; // Si aucune action précédente n'existe, initialise à 0
            }

            // Insérer le nouveau scan avec l'action déterminée
            $stmt = $pdo->prepare("INSERT INTO scans (badge_id, action, scan_date) VALUES (:badge_id, :action, NOW())");
            $stmt->bindParam(':badge_id', $badge['id']);
            $stmt->bindParam(':action', $newAction);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Scan ajouté avec succès!", "badge_id" => $badge['id'], "action" => $newAction]);
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
