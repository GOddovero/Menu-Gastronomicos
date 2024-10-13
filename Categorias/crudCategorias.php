<?php
function buscar_Categorias($db)
{
	try {
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;
		$sql = "SELECT * FROM categorias WHERE empresaid = :empresaid ORDER BY descripcion";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":empresaid", $empresaid);
		$stmt->execute();
		$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $categorias;
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al buscar categorías: " . $e->getMessage();
	}
}
function insert_update_Categoria($db)
{
	try {
		$id = $_POST['id'];
		$descripcion = trim($_POST['descripcion']);
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;
		$icono		=		trim($_POST['icono']);

		if (empty($descripcion)) {
			$_SESSION['error'] = "La descripción no puede estar vacía.";
			return;
		}
		if (strlen($descripcion) > 100) {
			$_SESSION['error'] = "La descripción no puede tener más de 100 caracteres.";
			return;
		}
		if (!preg_match('/^[a-zA-Z0-9\s.,-áéíóúÁÉÍÓÚñÑ]+$/', $descripcion)) {
			$_SESSION['error'] = "La descripción contiene caracteres no permitidos.";
			return;
		}

		if ($id > 0):
			$sql = "UPDATE categorias SET descripcion = :descripcion , icono = :icono WHERE id = :id";
			$stmt = $db->prepare($sql);
			$stmt->bindparam(":descripcion", $descripcion);
			$stmt->bindparam(":icono", $icono);
			$stmt->bindparam(":id", $id);
		else:
			$sql = "INSERT INTO categorias (descripcion,icono,empresaid)
					VALUES(:descripcion ,:icono,:empresaid)";
			$stmt = $db->prepare($sql);
			$stmt->bindparam(":descripcion", $descripcion);
			$stmt->bindparam(":icono", $icono);
			$stmt->bindparam(":empresaid", $empresaid);
		endif;
		if ($stmt->execute()) {
			$_SESSION['mensaje'] = "Categoria actualizada exitosamente.";
		} else {
			$_SESSION['error'] = "Error al actualizar Categoria.";
		}
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al actualizar Categoria: " . $e->getMessage();
	}
}
function eliminarCategoria($db)
{
	try {
		$id = $_POST['id'];
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;

		// Verificar si la categoría está siendo utilizada en la tabla productos
		$sql_check = "SELECT COUNT(*) FROM menu WHERE categoria_id = :id AND empresaid = :empresaid";
		$stmt_check = $db->prepare($sql_check);
		$stmt_check->bindParam(":id", $id);
		$stmt_check->bindParam(":empresaid", $empresaid);
		$stmt_check->execute();
		$count = $stmt_check->fetchColumn();

		if ($count > 0) {
			$_SESSION['error'] = "No se puede eliminar la categoría porque está siendo utilizada en productos.";
			return;
		}

		$sql = "DELETE FROM categorias WHERE id = :id AND empresaid = :empresaid";
		$stmt = $db->prepare($sql);
		$stmt->bindparam(":id", $id);
		$stmt->bindparam(":empresaid", $empresaid);

		if ($stmt->execute()) {
			$_SESSION['mensaje'] = "Categoría eliminada exitosamente.";
		} else {
			$_SESSION['error'] = "Error al eliminar la categoría. Revise que no se este usando en algun producto.";
		}
	} catch (PDOException $e) {
		$_SESSION['error'] = "Error al eliminar la categoría: " . $e->getMessage();
	}
}

function mostrarDataCategorias($categorias)
{
?>
<class="card mt-3">
	<div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
		<h5 class="card-title mb-0"></h5>
		<button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editCategoriaModal" onclick="clearCategoriaForm()">Agregar Categoría</button>
	</div>
	<div class="card-body">
		<table id="categorias-table" class="table table-striped">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Icono</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categorias as $categoria): ?>
				<tr>
					<td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
					<td>
						<?php
								// Verifica si el icono empieza con 'fa' para saber si es de FontAwesome
								if (strpos($categoria['icono'], 'fa') === 0) {
									// Es un icono de FontAwesome
									echo '<i class="' . htmlspecialchars($categoria['icono']) . '" style="font-size: 24px;"></i>';
								} else {
									// Es un icono de Bootstrap Icons
									echo '<i class="bi ' . htmlspecialchars($categoria['icono']) . '" style="font-size: 24px;"></i>';
								}
								?>
					</td>
					<td>
						<button class="btn btn-sm btn-primary edit-categoria" data-id="<?php echo $categoria['id']; ?>" data-empresaid="<?php echo $categoria['empresaid']; ?>" data-descripcion="<?php echo htmlspecialchars($categoria['descripcion']); ?>" data-icono="<?php echo htmlspecialchars($categoria['icono']); ?>" data-bs-toggle="modal" data-bs-target="#editCategoriaModal">
							Editar <i class="bi bi-pencil"></i>
						</button>
						<button class="btn btn-sm btn-danger delete-categoria" data-id="<?php echo $categoria['id']; ?>" data-empresaid="<?php echo $categoria['empresaid']; ?>">
							Eliminar <i class="bi bi-trash"></i>
						</button>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<script>
	$(document).ready(function() {
		const data = <?php echo json_encode($categorias); ?>; // Asegúrate de definir tu variable `data` con los datos necesarios
		const columns = [{
				data: 'descripcion',
				title: 'Descripción'
			},
			{
				data: 'icono',
				title: 'icono'
				render: function(data) {
					// Verifica si el icono empieza con 'fa' para saber si es de FontAwesome
					if (data.startsWith('fa')) {
						// Es un icono de FontAwesome
						return `<i class="${data}" style="font-size: 24px;"></i>`;
					} else {
						// Es un icono de Bootstrap Icons
						return `<i class="bi ${data}" style="font-size: 24px;"></i>`;
					}
				}
			},
			{
				data: null,
				title: 'Acciones',
				orderable: false,
				searchable: false,
				render: function(data, type, row) {
					return `
                        <button class="btn btn-sm btn-primary edit-categoria" data-id="${row.id}" data-descripcion="${row.descripcion}" data-bs-toggle="modal" data-bs-target="#editCategoriaModal">
                            Editar <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-categoria" data-id="${row.id}" >
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

		const table = $("#categorias-table").DataTable(tableOptions);
	});
	</script>

	<?php
}
