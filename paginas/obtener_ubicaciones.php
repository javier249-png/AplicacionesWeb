<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$pdo = obtenerConexion();

if ($action === 'comunas') {
    $id_region = (int)($_GET['id_region'] ?? 0);
    $stmt = $pdo->prepare("SELECT id_comuna, nombre_comuna FROM comuna WHERE id_region = :id_region ORDER BY nombre_comuna ASC");
    $stmt->execute(['id_region' => $id_region]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'recintos') {
    $id_comuna = (int)($_GET['id_comuna'] ?? 0);
    $stmt = $pdo->prepare("SELECT id_recinto, nombre_recinto, capacidad_personas FROM recinto WHERE id_comuna = :id_comuna ORDER BY nombre_recinto ASC");
    $stmt->execute(['id_comuna' => $id_comuna]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

echo json_encode([]);