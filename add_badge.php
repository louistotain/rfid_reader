<?php
require 'db.php';

header('Content-Type: application/json');

// Vérifier si la méthode est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données JSON
    $data = json_decode(file_get_contents('php://input'), true);
    $badge_id = $data['id_badge'] ?? null;

    if ($badge_id) {
        // Préparer la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO scans (badge_id) VALUES (:badge_id)");
        $stmt->bindParam(':badge_id', $badge_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "ID de badge ajouté avec succès!", "id_badge" => $badge_id]);
        } else {
            echo json_encode(["error" => "Erreur lors de l'ajout de l'ID de badge."]);
        }
    } else {
        echo json_encode(["error" => "L'ID du badge est requis."]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée. Utilisez POST."]);
}
?>
