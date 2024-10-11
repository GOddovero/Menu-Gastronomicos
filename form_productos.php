<!-- Modal para editar/agregar productos -->
<div class="modal fade" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-success text-white">
				<h5 class="modal-title" id="editProductoModalLabel">PRODUCTO</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="edit-producto-form" method="POST" action="administrame.php" enctype="multipart/form-data">
					<input type="hidden" name="id" id="edit-producto-id">
					<input type="hidden" name="empresaid" id="edit-producto-empresaid" value="<?php echo $_SESSION['empresaid']; ?>">
					<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

					<div class="form-group">
						<label for="producto-categoria">Categoría:</label>
						<select class="form-control" id="producto-categoria" name="categoria_id" required>
							<?php foreach ($categorias as $categoria): ?>
							<option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['descripcion']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="producto-nombre">Nombre:</label>
						<input type="text" class="form-control" id="producto-nombre" name="nombre" maxlength="100" required>
					</div>
					<div class="form-group">
						<label for="producto-descripcion">Descripción:</label>
						<input type="text" class="form-control" id="producto-descripcion" name="descripcion" maxlength="255" required>
					</div>
					<div class="form-group">
						<label for="producto-tamaño">Tamaño:</label>
						<input type="text" class="form-control" id="producto-tamaño" name="tamaño" maxlength="50" required>
					</div>
					<div class="form-group">
						<label for="producto-precio">Precio:</label>
						<input type="number" step="0.01" class="form-control" id="producto-precio" name="precio" required>
					</div>
					<div class="form-group">
						<label for="producto-imagen">Imagen:</label>
						<input type="file" class="form-control" id="producto-imagen" name="path_imagen" accept="image/*">
					</div>
					<button type="submit" class="btn btn-primary" name="edit_producto">Actualizar</button>
				</form>
			</div>
		</div>
	</div>
</div>
