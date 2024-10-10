<div class="modal fade" id="editCategoriaModal" tabindex="-1" aria-labelledby="editCategoriaModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-success text-white">
				<h5 class="modal-title" id="editCategoriaModalLabel">Editar Categoría</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="edit-categoria-form" method="POST" action="administrame.php" enctype="multipart/form-data">
					<input type="hidden" name="id" id="edit-categoria-id">
					<input type="hidden" name="empresaid" id="edit-categoria-empresaid">
					<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
					<div class="form-group">
						<label for="categoria-descripcion">Descripción:</label>
						<input type="text" class="form-control" id="categoria-descripcion" name="descripcion" maxlength="50" required>
					</div>

					<button type="submit" class="btn btn-primary" name="edit_categoria">Actualizar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</form>
			</div>
		</div>
	</div>
</div>
