<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

require_once("./Config/config.php");
require_once("./BD/Database.php");

use BD\Database;

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);
$porcentaje = isset($data['porcentaje']) ? $data['porcentaje'] : null;
$categoriaId = isset($data['categoriaId']) ? $data['categoriaId'] : null;

if ($porcentaje && $categoriaId) {
	$porcentaje = floatval($porcentaje);
	$categoriaId = intval($categoriaId);

	$query = "UPDATE menu SET precio = precio * (1 + ? / 100) WHERE categoria_id = ?";
	$stmt = $db->prepare($query);
	$stmt->bindParam(1, $porcentaje, PDO::PARAM_STR);
	$stmt->bindParam(2, $categoriaId, PDO::PARAM_INT);

	if ($stmt->execute()) {
		echo json_encode(['success' => true]);
	} else {
		echo json_encode(['success' => false, 'error' => $stmt->errorInfo()]);
	}
} else {
	echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
}
