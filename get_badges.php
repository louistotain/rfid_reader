<?php
require 'db.php';

header('Content-Type: application/json');

// Préparer et exécuter la requête de sélection avec jointure pour récupérer le nom de l'utilisateur et le type de scan
$stmt = $pdo->query("
    SELECT scans.id AS ID, scans.action AS Action, users.username AS Utilisateur, scans.scan_date AS Date_de_Scan
    FROM scans 
    JOIN user_badges ON scans.badge_id = user_badges.badge_id
    JOIN users ON user_badges.user_id = users.id
    ORDER BY scans.scan_date DESC;
");

$scans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Envoyer les résultats au format JSON
echo json_encode($scans);
?>
