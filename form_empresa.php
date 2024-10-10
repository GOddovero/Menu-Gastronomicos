<!-- Modal -->
<div class="modal fade" id="editEmpresaModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="editEmpresaModalLabel">Editar Empresa</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="edit-form" method="POST" action="administrame.php" enctype="multipart/form-data">
					<input type="hidden" name="id" id="edit-id">
					<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
					<div class="form-group">
						<label for="contacto">Contacto:</label>
						<input type="text" class="form-control" id="contacto" name="contacto" maxlength="150" required>
					</div>

					<div class="form-group">
						<label for="email">Email:</label>
						<input type="email" class="form-control" id="email" name="email" maxlength="100" required>
					</div>

					<div class="form-group">
						<label for="descripcion">Descripción:</label>
						<input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="50" required>
					</div>
					<div class="form-group">
						<label for="direccion">Dirección:</label>
						<input type="text" class="form-control" id="direccion" name="direccion" maxlength="80">
					</div>
					<div class="form-group">
						<label for="whatsapp">Whatsapp:</label>
						<input type="text" class="form-control" id="whatsapp" name="whatsapp" maxlength="80">
					</div>

					<div class="form-group">
						<label for="instagram">Instagram:</label>
						<input type="text" class="form-control" id="instagram" name="instagram" maxlength="80">
					</div>

					<div class="form-group">
						<label for="facebook">Facebook:</label>
						<input type="text" class="form-control" id="facebook" name="facebook" maxlength="80">
					</div>
					<div class="form-group">
						<label for="pagina_web">Página Web:</label>
						<input type="text" class="form-control" id="pagina_web" name="pagina_web" maxlength="80">
					</div>

					<div class="form-group">
						<label for="edit-logo">Logo:</label>
						<?php if (!empty($empresa['path_logo'])): ?>
						<div>
							<img src="<?php echo htmlspecialchars($empresa['path_logo']); ?>" alt="Logo de la empresa" style="max-width: 200px;">
						</div>
						<?php endif; ?>
						<input type="file" class="form-control" id="logo" name="logo" accept="image/*">
					</div>

					<button type="submit" class="btn btn-primary" name="edit_empresa">Actualizar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</form>
			</div>
		</div>
	</div>
</div>
