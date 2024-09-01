<?php
require 'db.php';

header('Content-Type: application/json');

// Préparer et exécuter la requête de sélection
$stmt = $pdo->query("SELECT * FROM scans ORDER BY scan_date DESC");
$scans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Envoyer les résultats au format JSON
echo json_encode($scans);
?>
