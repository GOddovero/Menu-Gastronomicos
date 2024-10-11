<?php
function editarEmpresa($db, $empresa)
{
	try {
		$id = $_POST['id'];
		$descripcion = trim($_POST['descripcion']);
		$contacto = trim($_POST['contacto']);
		$email = trim($_POST['email']);
		$direccion = trim($_POST['direccion']);
		$whatsapp = trim($_POST['whatsapp']);
		$instagram = trim($_POST['instagram']);
		$facebook = trim($_POST['facebook']);
		$pagina_web = trim($_POST['pagina_web']);
		$logo = isset($_FILES['logo']) ? $_FILES['logo'] : null;

		if (empty($descripcion)) {
			$_SESSION['error'] = "La descripción no puede estar vacía.";
			return;
		}
		if (strlen($descripcion) > 50) {
			$_SESSION['error'] = "La descripción no puede tener más de 50 caracteres.";
			return;
		}
		if (!preg_match('/^[a-zA-Z0-9\s.,-áéíóúÁÉÍÓÚñÑ]+$/', $descripcion)) {
			$_SESSION['error'] = "La descripción contiene caracteres no permitidos.";
			return;
		}


		if (empty($contacto)) {
			$_SESSION['error'] = "El contacto no puede estar vacío.";
			return;
		}
		if (strlen($contacto) > 50) {
			$_SESSION['error'] = "El contacto no puede tener más de 150 caracteres.";
			return;
		}

		if (!preg_match('/^[a-zA-Z0-9\s.,-áéíóúÁÉÍÓÚñÑ]+$/', $contacto)) {
			$_SESSION['error'] = "El contacto contiene caracteres no permitidos.";
			return;
		}


		if ($logo['size'] > 2 * 1024 * 1024) {
			$_SESSION['error'] = "El archivo es demasiado grande. El tamaño máximo permitido es de 2MB.";
			exit;
		}


		// Actualizar la descripción y otros campos en la base de datos
		$sql = "UPDATE empresa SET descripcion = :descripcion, contacto = :contacto, email = :email, direccion = :direccion, whatssap = :whatsapp, instagram = :instagram, facebook = :facebook, pagina_web = :pagina_web WHERE id = :id";
		$stmt = $db->prepare($sql);
		$stmt->bindparam(":descripcion", $descripcion);
		$stmt->bindparam(":contacto", $contacto);
		$stmt->bindparam(":email", $email);
		$stmt->bindparam(":direccion", $direccion);
		$stmt->bindparam(":whatsapp", $whatsapp);
		$stmt->bindparam(":instagram", $instagram);
		$stmt->bindparam(":facebook", $facebook);
		$stmt->bindparam(":pagina_web", $pagina_web);
		$stmt->bindparam(":id", $id);

		if ($stmt->execute()) {
			$_SESSION['mensaje'] = "Empresa actualizada exitosamente.";
		} else {
			$_SESSION['error'] = "Error al actualizar empresa.";
		}

		// Si se ha subido un nuevo archivo, guardarlo en el servidor y actualizar el path_logo
		if ($logo && $logo['error'] == UPLOAD_ERR_OK) {
			$target_dir = "img/" . $empresa['carpeta_graficos'] . "/";
			$target_file = $target_dir . basename($logo["name"]);

			if (move_uploaded_file($logo["tmp_name"], $target_file)) {
				// Actualizar la base de datos con la nueva información
				$query = "UPDATE empresa SET path_logo = ? WHERE id = ?";
				$stmt = $db->prepare($query);
				$stmt->execute([$target_file, $id]);

				$_SESSION['mensaje'] = "La empresa ha sido actualizada correctamente.";
			} else {
				$_SESSION['error'] = "Hubo un error al subir el archivo.";
			}
		}
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al actualizar empresa: " . $e->getMessage();
	}
}


function mostrarDataEmpresa($empresa)
{
?>
<div class="card-body">
	<div class="empresa-info">
		<div class="empresa-item">
			<strong>Descripción:</strong> <?php echo $empresa['descripcion']; ?> <i class="bi bi-pencil"></i>
		</div>
		<div class="empresa-item">
			<strong>Activo:</strong> <?php echo $empresa['activo'] ? 'Sí' : 'No'; ?> <i class="bi bi-check-circle"></i>
		</div>
		<div class="empresa-item">
			<strong>Contacto:</strong> <?php echo $empresa['contacto']; ?> <i class="bi bi-person"></i>
		</div>
		<div class="empresa-item">
			<strong>Email:</strong> <?php echo $empresa['email']; ?> <i class="bi bi-envelope"></i>
		</div>
		<div class="empresa-item">
			<strong>Dirección:</strong> <?php echo $empresa['direccion']; ?> <i class="bi bi-geo-alt"></i>
		</div>
		<div class="empresa-item">
			<strong>Whatsapp:</strong> <?php echo $empresa['whatssap']; ?> <i class="bi bi-whatsapp"></i>
		</div>
		<div class="empresa-item">
			<strong>Instagram:</strong> <?php echo $empresa['instagram']; ?> <i class="bi bi-instagram"></i>
		</div>
		<div class="empresa-item">
			<strong>Facebook:</strong> <?php echo $empresa['facebook']; ?> <i class="bi bi-facebook"></i>
		</div>
		<div class="empresa-item">
			<strong>Página Web:</strong> <?php echo $empresa['pagina_web']; ?> <i class="bi bi-globe"></i>
		</div>

		<div class="empresa-item">
			<strong>Logo:</strong>
			<?php if (!empty($empresa['path_logo'])): ?>
			<img src="<?php echo htmlspecialchars($empresa['path_logo']); ?>" alt="Logo de la empresa" style="max-width: 200px;">
			<?php else: ?>
			<span>No hay logo disponible</span>
			<?php endif; ?>
		</div>
		<div class="empresa-item">
			<button class="btn btn-sm btn-primary edit-empresa" data-id="<?php echo $empresa['id']; ?>" data-descripcion="<?php echo htmlspecialchars($empresa['descripcion']); ?>" data-activo="<?php echo $empresa['activo']; ?>" data-contacto="<?php echo htmlspecialchars($empresa['contacto']); ?>" data-email="<?php echo htmlspecialchars($empresa['email']); ?>" data-direccion="<?php echo htmlspecialchars($empresa['direccion']); ?>" data-whatssap="<?php echo htmlspecialchars($empresa['whatssap']); ?>" data-instagram="<?php echo htmlspecialchars($empresa['instagram']); ?>" data-facebook="<?php echo htmlspecialchars($empresa['facebook']); ?>" data-pagina_web="<?php echo htmlspecialchars($empresa['pagina_web']); ?>">
				Haceme un click para modificar los datos <i class="bi bi-pencil"></i>
			</button>
		</div>
	</div>
</div>
<?php
}
