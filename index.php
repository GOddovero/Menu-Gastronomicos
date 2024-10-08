<?php


/*

para usar :
http://localhost/menu/index.php?codigo=benita_cafe
*/
require_once("Config/config.php");
require_once("BD/database.php");
$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';

use BD\Database;

$database = new Database();
$db = $database->getConnection();

if ($codigo) {
	// Realizar la búsqueda en la base de datos
	$query = "SELECT * FROM empresa WHERE codigoQR = ?";
	$stmt = $db->prepare($query);
	$stmt->execute([$codigo]);
	$empresa = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($empresa) {
		$empresa = $empresa[0];
		// Mostrar los resultados o realizar alguna acción
		//echo "Resultado encontrado: " . htmlspecialchars($result['campo']);
	} else {
		echo "No se encontraron resultados para el código: " . htmlspecialchars($codigo);
		exit;
	}
} else {
	echo "No se proporcionó ningún código.";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $empresa['descripcion']; ?> - Menú</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="<?php echo  $empresa['style']; ?>">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&family=Open+Sans:wght@600&display=swap" rel="stylesheet">

</head>

<body>
	<button id="btn-back-to-top" class="btn btn-primary btn-floating btn-lg">
		<i class="fas fa-arrow-up"></i>
	</button>
	<header class="text-white text-center py-3" style="background-color: #1c1c1c;">
		<h1 class="display-4"><?php echo $empresa['descripcion'] ?></h1>
	</header>

	<div class=" container mt-4">
		<div class="text-center mb-4">
			<img src="<?php echo $empresa['path_logo'] ?>" alt="Logo de <?php echo $empresa['descripcion'] ?>" class="img-fluid rounded shadow w-50">
		</div>

		<div id="category-buttons" class="mb-4 text-center">
			<!-- Los botones de categoría se cargarán aquí dinámicamente -->
		</div>

		<div id="menu-container">
			<!-- Los elementos del menú se cargarán aquí dinámicamente -->
		</div>

	</div>
	<footer class="bg-dark text-white text-center py-3 mt-5">
		<div class="container">
			<div class="row">
				<div class="col">
					<a href="<?php echo $empresa['whatssap'] ?>" target="_blank" class="text-white me-3">
						<i class="fab fa-whatsapp fa-2x"></i>
					</a>
					<a href="<?php echo $empresa['instagram'] ?>" target="_blank" class="text-white me-3">
						<i class="fab fa-instagram fa-2x"></i>
					</a>
					<a href="<?php echo $empresa['facebook'] ?>" target="_blank" class="text-white">
						<i class="fab fa-facebook fa-2x"></i>
					</a>
				</div>
			</div>
		</div>
	</footer>

	<script>
		const empresaid = <?php echo json_encode($empresa['id']); ?>;
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="script/script.js"></script>
</body>

</html>