<?php require_once('./Config/iconos.php'); ?>

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
					<div class="form-group">
						<label for="categoria-icono">Icono:</label>
						<div id="icono-seleccionado" class="mb-2"></div>
						<input type="hidden" id="categoria-icono" name="icono" required>
						<div class="icono-seleccion">
							<?php
							foreach ($iconos as $categoria => $iconosPorCategoria) {
								echo "<h3>$categoria</h3>";
								foreach ($iconosPorCategoria as $icono) {
									echo '<i class="' . $icono . ' icono-opcion" data-icono="' . $icono . '" style="font-size: 24px; cursor: pointer; margin: 5px;"></i>';
								}
							}
							?>
						</div>
					</div>
					<button type="submit" class="btn btn-primary" name="edit_categoria">Actualizar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</form>
			</div>
		</div>
	</div>
</div>