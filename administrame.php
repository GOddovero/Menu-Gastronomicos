<?php
/*

para usar :
http://localhost:5500/administrame.php?codigo=benita_cafe

*/

require_once("./Config/config.php");
require_once("./BD/Database.php");
require_once("crudEmpresa.php");
require_once("crudCategorias.php");
require_once("crudProductos.php");

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

$database = new Database();
$db = $database->getConnection();

if (!$codigo) :
	echo "No se proporcionó ningún código.";
	exit;
endif;

$_SESSION['codigo'] = $codigo;
$query = "SELECT * FROM empresa WHERE codigoQR = ?";
$stmt = $db->prepare($query);
$stmt->execute([$codigo]);
$empresa = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($empresa):
	$empresa = $empresa[0];
	$_SESSION['empresaid'] = $empresa['id'];
else :
	echo "No se encontraron resultados para el código: " . htmlspecialchars($codigo);
	exit;
endif;

$query = "SELECT * FROM categorias WHERE empresaid = ? ORDER BY descripcion";
$stmt = $db->prepare($query);
$stmt->execute([$empresa['id']]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$categorias):
	$categorias = [];
endif;



// Procesar formularios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$mensaje = "";
	// Validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("CSRF token validation failed");
	}
	#edicion Empresa
	if (isset($_POST['edit_empresa'])) {
		editarEmpresa($db);
	}
	if (isset($_POST['edit_categoria'])) {
		insert_update_Categoria($db);
	}
	if (isset($_POST['delete_categoria'])) {
		eliminarCategoria($db);
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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" href="./styles/styleAdmin.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<!-- Tema oscuro -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.dark.min.css" id="dark-theme">
	<!-- Tema claro -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.light.min.css" id="light-theme">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.js"></script>
	<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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

		<h1 class="text-center mb-4">PANEL DE ADMNISTRACIÓN</h1>

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
			<!-- Tab Empresas -->
			<div class="tab-pane fade show active" id="empresas">
				<div class="card mt-3">
					<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Dato Empresa</h5>
					</div>
					<?php mostrarDataEmpresa($empresa); ?>
				</div>
			</div>
			<!-- Formulario de edición -->
			<?php require_once("form_empresa.php"); ?>
			<!-- Tab Categorías -->
			<div class="tab-pane fade" id="categorias">
				<div class="card mt-3">
					<div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Administración Categorías</h5>
					</div>
					<?php mostrarDataCategorias($categorias); ?>
				</div>
			</div>
			<!-- Formulario de edición de Categoría -->
			<?php require_once("form_categoria.php"); ?>

			<!-- Tab Productos -->
			<div class="tab-pane fade" id="productos">
				<div class="card mt-3">
					<div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Dato Producto</h5>
					</div>
					<?php mostrarDataProductos($producto); ?>
				</div>
			</div>
			<!-- Formulario de edición de Producto -->
			<?php require_once("form_productos.php"); ?>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="script/admin.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
		<script>
		// const data = []; // Asegúrate de definir tu variable `data` con los datos necesarios
		const columns = [{
				data: "descripcion",
				title: "Descripción"
			},
			{
				data: null,
				title: "Acciones",
				orderable: false,
				searchable: false,
				render: function(data, type, row) {
					return `
          <button class="btn btn-sm btn-primary edit-categoria" data-id="${row.id}" data-empresaid="${row.empresaid}" data-descripcion="${row.descripcion}" data-bs-toggle="modal" data-bs-target="#editCategoriaModal">
            Editar <i class="bi bi-pencil"></i>
          </button>
          <button class="btn btn-sm btn-danger delete-categoria" data-id="${row.id}" data-empresaid="${row.empresaid}">
            Eliminar <i class="bi bi-trash"></i>
          </button>
        `;
				},
			},
		];

		const tableOptions = {
			destroy: true,
			// data: data,
			responsive: {
				details: {
					type: "column",
					target: -1,
				},
			},
			language: {
				url: "https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
			},
			autoWidth: false,
			scroller: true,
			scrollX: true,
			scrollY: "50vh",
			dom: "Bfrtip",
			buttons: [{
					extend: "copy",
					text: "Copiar",
					exportOptions: {
						columns: ":not(:last-child)",
					},
				},
				{
					extend: "csv",
					text: "CSV",
					exportOptions: {
						columns: ":not(:last-child)",
					},
					charset: "utf-8",
					bom: true,
				},
				{
					extend: "excel",
					text: "Excel",
					exportOptions: {
						columns: ":not(:last-child)",
					},
				},
				{
					extend: "pdf",
					text: "PDF",
					exportOptions: {
						columns: ":not(:last-child)",
					},
				},
				{
					extend: "print",
					text: "Imprimir",
					exportOptions: {
						columns: ":not(:last-child)",
					},
				},
			],
			order: [
				[0, "desc"]
			],
			columns: columns,
		};

		$("#categorias-table").DataTable(tableOptions);
		</script>
</body>
<?php
