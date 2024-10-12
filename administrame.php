<?php
/*

para usar :
http://localhost:5500/administrame.php?codigo=benita_cafe

*/

require_once("./Config/config.php");
require_once("./BD/Database.php");
require_once("./Empresa/crudEmpresa.php");
require_once("./Categorias/crudCategorias.php");
require_once("./Productos/crudProductos.php");

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : (isset($_SESSION['codigo']) ? $_SESSION['codigo'] : '');

if ($codigo == "") {
	$codigo = isset($_SESSION['codigo']) ? $_SESSION['codigo'] : '';
}


use BD\Database;

try {
	$database = new Database();
	$db = $database->getConnection();
} catch (PDOException $e) {
	header('Location: error.php');
	exit;
}

if (empty($codigo)) {
	header('Location: error.php');

	exit;
}
$_SESSION['codigo'] = $codigo;

try {
	$empresa = getEmpresa($db, $codigo);
	if ($empresa) {
		$empresa = $empresa[0];
		$_SESSION['empresaid'] = $empresa['id'];
	} else {
		header('Location: error.php');
		exit;
	}
} catch (Exception $e) {
	header('Location: error.php');
	exit;
}

$query = "SELECT * FROM estilos WHERE activo='SI'";
$stmt = $db->prepare($query);
$stmt->execute();
$estilos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$estilos):
	$estilos = [];
endif;

$categorias = buscar_Categorias($db);
$productos = buscar_Productos($db);




// Procesar formularios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$mensaje = "";
	// Validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("CSRF token validation failed");
	}
	#edicion Empresa
	if (isset($_POST['edit_empresa'])) {
		editarEmpresa($db, $empresa);
	}
	if (isset($_POST['edit_categoria'])) {
		insert_update_Categoria($db);
	}
	if (isset($_POST['delete_categoria'])) {
		eliminarCategoria($db);
	}
	if (isset($_POST['edit_producto'])) {
		insert_update_productos($db, $empresa);
	}
	if (isset($_POST['delete_producto'])) {
		eliminarProducto($db);
	}
	header("Location: " . $_SERVER['PHP_SELF']);
	exit();
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Administración de la Empresa, Categorías y Productos</title>
	<meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap Icons -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css" rel="stylesheet">

	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

	<!-- FullCalendar CSS -->
	<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="./styles/styleAdmin.css">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Bootstrap Bundle JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- DataTables JS -->
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

	<!-- PDFMake and JSZip for DataTables export buttons -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

	<!-- jsPDF and html2canvas for custom PDF generation -->
	<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

</head>

<body>
	<div class="container mt-5">
		<?php
		if (isset($_SESSION['mensaje'])) {
			echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '" . $_SESSION['mensaje'] . "'
            });
        </script>";
			unset($_SESSION['mensaje']);
		}
		if (isset($_SESSION['error'])) {
			echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '" . $_SESSION['error'] . "'
            });
        </script>";
			unset($_SESSION['error']);
		}
		?>

		<div class="container mt-4">
			<h3 class="text-center mb-4" data-aos="fade-down" data-aos-once="true">PANEL DE ADMINISTRACIÓN</h3>

			<!-- Tabs para navegación -->
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="empresas-tab" data-bs-toggle="tab" data-bs-target="#empresas" type="button">Empresa</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="categorias-tab" data-bs-toggle="tab" data-bs-target="#categorias" type="button">Categorías</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="productos-tab" data-bs-toggle="tab" data-bs-target="#productos" type="button">Productos</button>
				</li>
			</ul>

			<div class="tab-content" id="myTabContent">
				<!-- Tab Empresa -->
				<div class="tab-pane fade show active" id="empresas" role="tabpanel" aria-labelledby="empresas-tab">
					<div class="card mt-3">
						<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
							<h5 class="card-title mb-0">Empresa</h5>
						</div>
						<?php mostrarDataEmpresa($empresa, $estilos); ?>
					</div>
				</div>

				<!-- Tab Categorías -->
				<div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
					<div class="card mt-3">
						<div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
							<h5 class="card-title mb-0">Categorías</h5>
						</div>
						<div class="card-body">
							<?php mostrarDataCategorias($categorias); ?>
						</div>
					</div>
					<!-- Formulario de edición de Categoría -->
					<?php require_once("./Categorias/form_categoria.php"); ?>
				</div>

				<!-- Tab Productos -->
				<div class="tab-pane fade" id="productos" role="tabpanel" aria-labelledby="productos-tab">
					<div class="card mt-3 data-aos=" fade-up" data-aos-duration="1000"">
						<div class=" card-header bg-warning text-white d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Productos</h5>
					</div>
					<div class="card-body">
						<?php mostrarDataProductos($productos, $categorias); ?>
					</div>
				</div>
				<!-- Formulario de edición de Producto -->
				<?php require_once("./Productos/form_productos.php"); ?>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="script/admin.js"></script>
	<script src="script/categoria.js"></script>
	<script src="script/producto.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
	<script>
		AOS.init();
	</script>
	<!-- Footer -->
	<footer class="bg-dark text-white text-center py-3 mt-4" data-aos="fade-up" data-aos-once="true">
		<p class="mb-0">🐍nake🐍oftDev - Todos los derechos reservados</p>
		<a href="http://snakesoftdev.com.ar/" target="_blank" class="text-white">http://snakesoftdev.com.ar/</a>
	</footer>
</body>
<?php
