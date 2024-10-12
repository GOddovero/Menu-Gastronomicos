<?php
function buscar_Productos($db)
{
	try {
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;
		$sql = "SELECT m.id, m.categoria_id, m.nombre, m.descripcion, m.tamaño, m.precio, m.path_imagen, c.descripcion AS categoria FROM menu m INNER JOIN categorias c ON m.categoria_id = c.id WHERE m.empresaid = :empresaid ORDER BY m.nombre";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":empresaid", $empresaid);
		$stmt->execute();
		$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $productos;
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al buscar productos: " . $e->getMessage();
	}
}
function insert_update_productos($db, $empresa)
{
	try {
		$id = $_POST['id'];
		$categoria_id = $_POST['categoria_id'];
		$nombre = trim($_POST['nombre']);
		$descripcion = trim($_POST['descripcion']);
		$tamano = trim($_POST['tamaño']);
		$precio = $_POST['precio'];
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;
		$path_imagen = isset($_FILES['path_imagen']) ? $_FILES['path_imagen'] : null;

		// Validar los campos
		if (empty($nombre) || empty($descripcion) || empty($precio)) {
			$_SESSION['error'] = "Todos los campos son obligatorios.";
			return;
		}

		// Validar longitud de los campos
		if (strlen($nombre) > 100 || strlen($descripcion) > 255 || strlen($tamano) > 50) {
			$_SESSION['error'] = "Uno o más campos exceden la longitud permitida.";
			return;
		}

		// Validar caracteres permitidos en la descripción
		if (!preg_match('/^[a-zA-Z0-9\s.,-áéíóúÁÉÍÓÚñÑ]+$/', $descripcion)) {
			$_SESSION['error'] = "La descripción contiene caracteres no permitidos.";
			return;
		}

		// Validar que el precio sea un número positivo
		if (!is_numeric($precio) || $precio <= 0) {
			$_SESSION['error'] = "El precio debe ser un número positivo.";
			return;
		}

		if ($id > 0) {
			// Actualizar producto existente
			$sql = "UPDATE menu SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, tamaño = :tamano, precio = :precio WHERE id = :id AND empresaid = :empresaid";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":categoria_id", $categoria_id);
			$stmt->bindParam(":nombre", $nombre);
			$stmt->bindParam(":descripcion", $descripcion);
			$stmt->bindParam(":tamano", $tamano);
			$stmt->bindParam(":precio", $precio);
			$stmt->bindParam(":id", $id);
			$stmt->bindParam(":empresaid", $empresaid);
		} else {
			// Insertar nuevo producto
			$sql = "INSERT INTO menu (categoria_id, nombre, descripcion, tamaño, precio, empresaid) VALUES (:categoria_id, :nombre, :descripcion, :tamano, :precio, :empresaid)";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":categoria_id", $categoria_id);
			$stmt->bindParam(":nombre", $nombre);
			$stmt->bindParam(":descripcion", $descripcion);
			$stmt->bindParam(":tamano", $tamano);
			$stmt->bindParam(":precio", $precio);
			$stmt->bindParam(":empresaid", $empresaid);
		}

		if ($stmt->execute()) {
			// Obtener el ID del producto insertado o actualizado
			$producto_id = $id > 0 ? $id : $db->lastInsertId();

			// Si se ha subido un nuevo archivo, guardarlo en el servidor y actualizar el path_imagen
			if ($path_imagen && $path_imagen['error'] == UPLOAD_ERR_OK) {
				// Verificar el tamaño del archivo
				if ($path_imagen['size'] > 2 * 1024 * 1024) {
					$_SESSION['error'] = "El archivo es demasiado grande. El tamaño máximo permitido es de 2MB.";
					return;
				}

				$target_dir = "img/" . $empresa['carpeta_graficos'] . "/";
				$target_file = $target_dir . basename($path_imagen["name"]);

				if (move_uploaded_file($path_imagen["tmp_name"], $target_file)) {
					// Actualizar la base de datos con la nueva información
					$query = "UPDATE menu SET path_imagen = :path_imagen WHERE id = :id";
					$stmt = $db->prepare($query);
					$stmt->bindParam(":path_imagen", $target_file);
					$stmt->bindParam(":id", $producto_id);
					$stmt->execute();

					$_SESSION['mensaje'] = "Producto actualizado correctamente.";
				} else {
					$_SESSION['error'] = "Hubo un error al subir el archivo.";
				}
			} else {
				$_SESSION['mensaje'] = "Producto actualizado exitosamente.";
			}
		} else {
			$_SESSION['error'] = "Error al actualizar producto.";
		}
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al actualizar producto: " . $e->getMessage();
	}
}

function eliminarProducto($db)
{
	try {
		$id = $_POST['id'];
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;

		$sql = "DELETE FROM menu WHERE id = :id AND empresaid = :empresaid";
		$stmt = $db->prepare($sql);
		$stmt->bindparam(":id", $id);
		$stmt->bindparam(":empresaid", $empresaid);

		if ($stmt->execute()) {
			$_SESSION['mensaje'] = "Producto eliminado exitosamente.";
		} else {
			$_SESSION['error'] = "Error al eliminar el producto.";
		}
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al eliminar el producto: " . $e->getMessage();
	}
}
function mostrarDataProductos($productos, $categorias)
{
?>
	<div class="card mt-3">
		<div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0">Productos</h5>
			<button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editProductoModal" onclick="clearProductoForm()">Agregar Producto</button>
		</div>
		<div class="card-body">
			<?php if (empty($productos)): ?>
				<div class="alert alert-info text-center" role="alert">
					😊 INGRESE SU PRIMER PRODUCTO 😊
				</div>
			<?php else: ?>
				<div class="d-flex justify-content-between align-items-center mb-3">
					<div class="input-group">
						<input type="number" class="form-control" id="aumentoPorcentaje" placeholder="Aumentar %" min="0" step="0.01">
						<select class="form-select" id="categoriaSelect">
							<option value="">Seleccione una categoría</option>
							<?php foreach ($categorias as $categoria): ?>
								<option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['descripcion']); ?></option>
							<?php endforeach; ?>
						</select>
						<button class="btn btn-primary" onclick="aplicarAumento()">Aplicar Aumento</button>
					</div>
				</div>
				<table id="productos-table" class="table table-striped">
					<thead>
						<tr>
							<th>Categoría</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Tamaño</th>
							<th>Precio</th>
							<th>Imagen</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($productos as $producto): ?>
							<tr>
								<td><?php echo htmlspecialchars($producto['categoria']); ?></td>
								<td><?php echo htmlspecialchars($producto['nombre']); ?></td>
								<td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
								<td><?php echo htmlspecialchars($producto['tamaño']); ?></td>
								<td><?php echo htmlspecialchars($producto['precio']); ?></td>
								<td><img src="<?php echo htmlspecialchars($producto['path_imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="50"></td>
								<td>
									<button class="btn btn-sm btn-primary edit-producto" data-aos="zoom-in" data-id="<?php echo $producto['id']; ?>" data-categoria_id="<?php echo $producto['categoria_id']; ?>" data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>" data-descripcion="<?php echo htmlspecialchars($producto['descripcion']); ?>" data-tamaño="<?php echo htmlspecialchars($producto['tamaño']); ?>" data-precio="<?php echo htmlspecialchars($producto['precio']); ?>" data-bs-toggle="modal" data-bs-target="#editProductoModal">
										Editar <i class="bi bi-pencil"></i>
									</button>
									<button class="btn btn-sm btn-danger delete-producto" data-id="<?php echo $producto['id']; ?>" data-empresaid="<?php echo $producto['empresaid']; ?>">
										Eliminar <i class="bi bi-trash"></i>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
	<?php require_once 'form_productos.php'; ?>

	<script>
		$(document).ready(function() {
			const data = <?php echo json_encode($productos); ?>; // Asegúrate de definir tu variable `data` con los datos necesarios
			const columns = [{
					data: 'categoria',
					title: 'Categoría'
				},
				{
					data: 'nombre',
					title: 'Nombre'
				},
				{
					data: 'descripcion',
					title: 'Descripción'
				},
				{
					data: 'tamaño',
					title: 'Tamaño'
				},
				{
					data: 'precio',
					title: 'Precio'
				},
				{
					data: 'path_imagen',
					title: 'Imagen',
					render: function(data) {
						return `<img src="${data}" alt="Imagen del producto" width="50">`;
					}
				},
				{
					data: null,
					title: 'Acciones',
					orderable: false,
					searchable: false,
					render: function(data, type, row) {
						return `
                        <button class="btn btn-sm btn-primary edit-producto" data-id="${row.id}" data-categoria_id="${row.categoria_id}" data-nombre="${row.nombre}" data-descripcion="${row.descripcion}" data-tamaño="${row.tamaño}" data-precio="${row.precio}" data-bs-toggle="modal" data-bs-target="#editProductoModal">
                            Editar <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-producto" data-id="${row.id}" data-empresaid="${row.empresaid}">
                            Eliminar <i class="bi bi-trash"></i>
                        </button>
                    `;
					}
				}
			];

			const tableOptions = {
				destroy: true,
				data: data,
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

			const table = $("#productos-table").DataTable(tableOptions);
		});
	</script>

	</div>


	<script>
		function aplicarAumento() {
			const porcentaje = document.getElementById('aumentoPorcentaje').value;
			const categoriaId = document.getElementById('categoriaSelect').value;

			if (porcentaje && categoriaId) {
				fetch('aplicarAumento.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							porcentaje: porcentaje,
							categoriaId: categoriaId
						})
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							alert('Aumento aplicado correctamente');
							location.reload(); // Recargar la página para ver los cambios
						} else {
							alert('Error al aplicar el aumento');
						}
					})
					.catch(error => console.error('Error:', error));
			} else {
				alert('Por favor, ingrese un porcentaje y seleccione una categoría');
			}
		}
	</script>
<?php
}
