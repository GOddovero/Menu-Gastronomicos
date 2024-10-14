<?php
/*

para usar :
http://localhost/menu/index.php?codigo=benita_cafe
*/

require_once("./Config/config.php");
require_once("./BD/Database.php");
require_once("./Empresa/crudEmpresa.php");

$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';

use BD\Database;

$database = new Database();
$db = $database->getConnection();

if ($codigo) {
	try {
		$empresa = getEmpresa($db, $codigo);
		if ($empresa) {
			$empresa = $empresa[0];
			if ($empresa['activo'] == 'NO') {
				$error_message = urlencode("La empresa no está activa. Por favor, contacte al administrador.");
				header("Location: error.php?error_message=$error_message");
				exit;
			}
			$_SESSION['empresaid'] = $empresa['id'];
		} else {
			header('Location: error.php');
			exit;
		}
	} catch (Exception $e) {
		header('Location: error.php');
		exit;
	}
} else {
	header('Location: error.php');
	exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $empresa['descripcion'];
			?> - Menú</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="./styles/<?php echo  $empresa['archivo_css'];
											?>">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&family=Open+Sans:wght@600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
</head>

<body>
	<button id="btn-back-to-top" class="btn btn-primary btn-floating btn-lg" data-aos="fade-up" data-aos-duration="1000">
		<i class="fas fa-arrow-up"></i>
	</button>
	<header class="text-white text-center py-3" style="background-color: #1c1c1c;" data-aos="fade-down" data-aos-duration="1000">
		<h1 class="display-4"><?php echo $empresa['descripcion'];
								?></h1>
	</header>

	<div class=" container mt-4">
		<div class="text-center mb-4" data-aos="zoom-in" data-aos-duration="1000">
			<img src="<?php echo $empresa['path_logo'];
						?>" alt="Logo de <?php echo $empresa['descripcion'];
											?>" class="img-fluid rounded shadow w-50">
		</div>

		<div id="category-buttons" class="mb-4 text-center" data-aos="fade-up" data-aos-duration="1000">
		</div>

		<div id="menu-container" data-aos="fade-up" data-aos-duration="1000">
		</div>

	</div>
	<footer class="bg-dark text-white text-center py-3 mt-5" data-aos="fade-up" data-aos-duration="1000">
		<div class="container">
			<div class="row">
				<div class="col">
					<a href=" <?php echo $empresa['whatsapp']; ?>" target="_blank" class="text-white me-3">
						<i class="fab fa-whatsapp fa-2x"></i>
					</a>
					<a href="<?php echo $empresa['instagram'];
								?>" target="_blank" class="text-white me-3">
						<i class="fab fa-instagram fa-2x"></i>
					</a>
					<a href="<?php echo $empresa['facebook'];
								?>" target="_blank" class="text-white">
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
	<script src="script/script.js"></script>
	<script src="script/<?php echo $empresa['archivo_js'] ?>"></script>
	<script>
	AOS.init({
		duration: 1000, // Duración de la animación en milisegundos
		once: true // Asegura que la animación se ejecute solo una vez
	});
	</script>
</body>

</html>
