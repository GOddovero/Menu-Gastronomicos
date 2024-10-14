<?php
require_once("./Config/config.php");
require_once("./BD/Database.php");

use BD\Database;


$empresaid = isset($_GET['empresaid']) ? $_GET['empresaid'] : '';
if ($empresaid) {
	$database = new Database();
	$db = $database->getConnection();
	$query = "SELECT categorias.descripcion as categoria, nombre, menu.descripcion, tamaño, precio, path_imagen
				FROM menu
				inner join categorias on menu.categoria_id = categorias.id
				Where menu.empresaid = :empresaid";

	$stmt = $db->prepare($query);
	$stmt->bindParam("empresaid", $empresaid);

	$stmt->execute();

	$menuData = [];
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$categoria = $row['categoria'];

		// Si la categoría no existe, inicializamos un array vacío para ella
		if (!isset($menuData[$categoria])) {
			$menuData[$categoria] = [];
		}

		// Creamos un array con los datos del producto
		$producto = [
			'nombre' => $row['nombre'],
			'descripcion' => $row['descripcion'],
			'tamaño' => $row['tamaño'],
			'precio' => $row['precio'],
			'imagen' => $row['path_imagen']
		];

		// Añadimos el producto al array de su categoría
		$menuData[$categoria][] = $producto;
	}

	header('Content-Type: application/json');
	echo json_encode($menuData);
} else {
	echo json_encode(["error" => "No se proporcionó ningún empresaid."]);
}
