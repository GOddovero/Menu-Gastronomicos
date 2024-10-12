<?php
function getEmpresa($db, $codigo)
{
	try {
		$query = "select e.id
			,e.descripcion
			,e.direccion
			,e.whatsapp
			,e.instagram
			,e.facebook
			,e.pagina_web
			,e.path_logo
			,e.activo
			,e.codigoQR
			,e.carpeta_graficos
			,e.contacto
			,e.email
			,e.estilo_id
			,ifnull(e2.descripcion,'')  as estilo
			,ifnull(e2.archivo_css,'') as archivo_css
			,ifnull(e2.archivo_js,'') as archivo_js
		from empresa e
		left join estilos e2 on e2.id  = e.estilo_id
		WHERE e.codigoQR = ?";
		$stmt = $db->prepare($query);
		$stmt->execute([$codigo]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		header('Location: error.php');
		exit;
	}
}
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
		$estilo_id = $_POST['estilo_id'];
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
		$sql = "UPDATE empresa SET descripcion = :descripcion, contacto = :contacto, email = :email, direccion = :direccion, whatsapp = :whatsapp, instagram = :instagram, facebook = :facebook, pagina_web = :pagina_web, estilo_id = :estilo_id  WHERE id = :id";
		$stmt = $db->prepare($sql);
		$stmt->bindparam(":descripcion", $descripcion);
		$stmt->bindparam(":contacto", $contacto);
		$stmt->bindparam(":email", $email);
		$stmt->bindparam(":direccion", $direccion);
		$stmt->bindparam(":whatsapp", $whatsapp);
		$stmt->bindparam(":instagram", $instagram);
		$stmt->bindparam(":facebook", $facebook);
		$stmt->bindparam(":pagina_web", $pagina_web);
		$stmt->bindparam(":estilo_id", $estilo_id);
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


function mostrarDataEmpresa($empresa, $estilos)
{

?>
	<div class="modal-body p-4">
		<div class="card shadow">
			<div class="card-body">
				<form id="edit-form" method="POST" action="administrame.php" enctype="multipart/form-data">
					<input type="hidden" name="id" id="edit-id" value="<?php echo htmlspecialchars($empresa['id']); ?>">
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

					<div class="mb-3">
						<label for="contacto" class="form-label">Contacto:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-person"></i></span>
							<input type="text" class="form-control" id="contacto" name="contacto" maxlength="150" required value="<?php echo htmlspecialchars($empresa['contacto']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="email" class="form-label">Email:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-envelope"></i></span>
							<input type="email" class="form-control" id="email" name="email" maxlength="100" required value="<?php echo htmlspecialchars($empresa['email']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="descripcion" class="form-label">Descripción:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-card-text"></i></span>
							<input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="50" required value="<?php echo htmlspecialchars($empresa['descripcion']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="estiloSelect" class="form-label">Estilo:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-palette"></i></span>
							<select id="estiloSelect" name="estilo_id" class="form-select">
								<option value="">Seleccione un estilo</option>
								<?php foreach ($estilos as $estilo): ?>
									<option value="<?php echo htmlspecialchars($estilo['id']); ?>" <?php echo $empresa['estilo_id'] == $estilo['id'] ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($estilo['descripcion']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="mb-3">
						<label for="direccion" class="form-label">Dirección:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
							<input type="text" class="form-control" id="direccion" name="direccion" maxlength="80" value="<?php echo htmlspecialchars($empresa['direccion']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="whatsapp" class="form-label">Whatsapp:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
							<input type="text" class="form-control" id="whatsapp" name="whatsapp" maxlength="80" value="<?php echo htmlspecialchars($empresa['whatsapp']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="instagram" class="form-label">Instagram:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-instagram"></i></span>
							<input type="text" class="form-control" id="instagram" name="instagram" maxlength="80" value="<?php echo htmlspecialchars($empresa['instagram']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="facebook" class="form-label">Facebook:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-facebook"></i></span>
							<input type="text" class="form-control" id="facebook" name="facebook" maxlength="80" value="<?php echo htmlspecialchars($empresa['facebook']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="pagina_web" class="form-label">Página Web:</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-globe"></i></span>
							<input type="text" class="form-control" id="pagina_web" name="pagina_web" maxlength="80" value="<?php echo htmlspecialchars($empresa['pagina_web']); ?>">
						</div>
					</div>

					<div class="mb-3">
						<label for="edit-logo" class="form-label">Logo:</label>
						<?php if (!empty($empresa['path_logo'])): ?>
							<div class="mb-3">
								<img src="<?php echo htmlspecialchars($empresa['path_logo']); ?>" alt="Logo de la empresa" class="img-fluid" style="max-width: 200px;">
							</div>
						<?php endif; ?>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-image"></i></span>
							<input type="file" class="form-control" id="logo" name="logo" accept="image/*">
						</div>
					</div>

					<div class="d-flex justify-content-end">
						<button type="submit" class="btn btn-primary me-2" name="edit_empresa">Actualizar</button>
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
}
