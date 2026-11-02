<?php
require_once __DIR__ . '/auth.php';
requireLogin();
// Recibir únicamente los 6 campos requeridos desde POST
$nombre      = $_POST['nombre'] ?? '';
$fecha       = $_POST['fecha'] ?? '';
$hora        = $_POST['hora'] ?? '';
$lugar       = $_POST['lugar'] ?? '';
$categoria   = $_POST['categoria'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';

// Cargar la plantilla de vista
include 'resumen_evento_vista.php';
?>