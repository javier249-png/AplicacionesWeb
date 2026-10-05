<?php
// Recibir variables para precargarlas en el formulario
$nombre      = $_POST['nombre'] ?? '';
$fecha       = $_POST['fecha'] ?? '';
$hora        = $_POST['hora'] ?? '';
$lugar       = $_POST['lugar'] ?? '';
$categoria   = $_POST['categoria'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';

// Cargar la plantilla de vista
include 'editar_evento_vista.php';
?>