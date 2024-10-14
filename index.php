<?php
/*

para usar :
http://localhost/menu/index.php?codigo=benita_cafe
http://localhost:5500/menu/index.php?codigo=benita_cafe
*/

require_once("./Config/config.php");
require_once("./BD/Database.php");
require_once("./Empresa/crudEmpresa.php");
require_once("./Empresa/Visitas.php");

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
$visitas = getVisitas($db, $empresa['id']);

// Cargar la galletita de la suerte
$fortune = include('./Config/fortune_cookie.php');
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
	<style>
	.fortune-cookie {
		background-color: #f8f9fa;
		border: 2px solid #ffc107;
		border-radius: 20px;
		padding: 20px;
		margin: 20px auto;
		max-width: 90%;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
		position: relative;
		overflow: hidden;
		animation: pulse 3s infinite;
	}

	.fortune-cookie:before {
		content: '';
		position: absolute;
		top: -50%;
		left: -50%;
		width: 200%;
		height: 200%;
		background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0) 70%);
		animation: shine 4s infinite;
	}

	.fortune-cookie i {
		color: #ffc107;
		margin-right: 10px;
		font-size: 1.5em;
		animation: bounce 2s infinite;
	}

	.fortune-cookie p {
		margin: 10px 0 0;
		font-size: 1.1em;
		font-weight: 500;
		color: #333;
	}

	@keyframes pulse {
		0% {
			transform: scale(1);
		}

		50% {
			transform: scale(1.05);
		}

		100% {
			transform: scale(1);
		}
	}

	@keyframes shine {
		0% {
			transform: rotate(0deg);
			opacity: 0;
		}

		25% {
			opacity: 0.5;
		}

		50% {
			transform: rotate(180deg);
			opacity: 0;
		}

		100% {
			transform: rotate(360deg);
			opacity: 0;
		}
	}

	@keyframes bounce {

		0%,
		20%,
		50%,
		80%,
		100% {
			transform: translateY(0);
		}

		40% {
			transform: translateY(-10px);
		}

		60% {
			transform: translateY(-5px);
		}
	}

	/* Media queries for responsiveness */
	@media (max-width: 768px) {
		.fortune-cookie {
			padding: 15px;
			margin: 15px auto;
		}

		.fortune-cookie i {
			font-size: 1.3em;
		}

		.fortune-cookie p {
			font-size: 1em;
		}
	}

	@media (max-width: 576px) {
		.fortune-cookie {
			padding: 12px;
			margin: 10px auto;
			border-radius: 15px;
		}

		.fortune-cookie i {
			font-size: 1.2em;
		}

		.fortune-cookie p {
			font-size: 0.9em;
		}
	}

	</style>
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
		<!-- Galletita de la suerte -->
		<div class="fortune-cookie text-center" data-aos="fade-up" data-aos-duration="1000">
			<i class="fas fa-cookie-bite fa-2x"></i>
			<p class="mb-0"><?php echo $fortune; ?></p>
		</div>

		<!-- Mostrar el contador de visitas -->
		<div class="text-center mt-4" data-aos="fade-up" data-aos-duration="1000">
			<?php echo "<small>¡Felicitaciones!vos hiciste el escaneado nº: $visitas, podes hacer un click en los botones de aca abajo para filtrar por categoria</small>"; ?>
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
