<?php

function insert_update_Categoria($db)
{
	try {
		$id = $_POST['id'];
		$descripcion = trim($_POST['descripcion']);
		$empresaid = isset($_SESSION['empresaid']) ? $_SESSION['empresaid'] : null;


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
			$sql = "UPDATE categorias SET descripcion = :descripcion  WHERE id = :id";
			$stmt = $db->prepare($sql);
			$stmt->bindparam(":descripcion", $descripcion);
			$stmt->bindparam(":id", $id);
		else:
			$sql = "INSERT INTO categorias (descripcion,empresaid)
					VALUES(:descripcion ,:empresaid)";
			$stmt = $db->prepare($sql);
			$stmt->bindparam(":descripcion", $descripcion);
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
		$empresaid = $_POST['empresaid'];

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
		<h5 class="card-title mb-0">Categorías</h5>
		<button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editCategoriaModal" onclick="clearCategoriaForm()">Agregar Categoría</button>
	</div>
	<div class="card-body">
		<table id="categorias-table" class="table table-striped">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categorias as $categoria): ?>
				<tr>
					<td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
					<td>
						<button class="btn btn-sm btn-primary edit-categoria" data-id="<?php echo $categoria['id']; ?>" data-empresaid="<?php echo $categoria['empresaid']; ?>" data-descripcion="<?php echo htmlspecialchars($categoria['descripcion']); ?>" data-bs-toggle="modal" data-bs-target="#editCategoriaModal">
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
	</class=>
	<?php
}
