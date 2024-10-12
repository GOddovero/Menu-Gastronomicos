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
							$iconos = [
								'comida' => [
									// Bootstrap Icons
									'bi-cake',
									'bi-cup',
									'bi-cup-hot',
									'bi-egg',
									'bi-egg-fried',

									// FontAwesome
									'fas fa-pizza-slice',
									'fas fa-ice-cream',
									'fas fa-apple-alt',
									'fas fa-bacon',
									'fas fa-hotdog',
									'fas fa-hamburger',
									'fas fa-drumstick-bite', // Pollo
									'fas fa-fish',         // Pescado
									'fas fa-cheese',       // Queso
									'fas fa-bread-slice',  // Pan
									'fas fa-cookie',       // Galleta
									'fas fa-cookie-bite',  // Galleta mordida
									'fas fa-carrot',       // Zanahoria
									'fas fa-lemon',        // Limón
									'fas fa-pepper-hot',   // Pimiento
									'fas fa-apple-whole',  // Manzana entera
									'fas fa-seedling',     // Planta / Brote
									'fas fa-wine-bottle',  // Botella de vino
									'fas fa-mug-hot',      // Taza caliente
									'fas fa-coffee',       // Café
									'fas fa-beer',         // Cerveza
									'fas fa-cocktail',     // Cóctel
									'fas fa-blender',      // Licuadora
									'fas fa-glass-whiskey', // Whisky
									'fas fa-wine-glass',   // Copa de vino
									'fas fa-wine-glass-alt', // Copa de vino alternativa
								],

								// Objetos
								'objetos' => [
									'bi-box',            // Bootstrap Icons
									'bi-bag',            // Bootstrap Icons
									'fas fa-box',        // FontAwesome
									'fas fa-shopping-bag', // FontAwesome
									'fas fa-coffee',      // FontAwesome
								],

								// Transporte
								'transporte' => [
									'bi-truck',          // Bootstrap Icons
									'bi-bicycle',        // Bootstrap Icons
									'fas fa-car',        // FontAwesome
									'fas fa-bus',        // FontAwesome
									'fas fa-motorcycle', // FontAwesome
								],

								// Tecnología
								'tecnologia' => [
									'bi-wifi',           // Bootstrap Icons
									'bi-telephone',      // Bootstrap Icons
									'fas fa-laptop',     // FontAwesome
									'fas fa-mobile-alt', // FontAwesome
									'fas fa-camera',     // FontAwesome
								],

								// Personas
								'personas' => [
									'bi-person',         // Bootstrap Icons
									'bi-people',         // Bootstrap Icons
									'fas fa-user',       // FontAwesome
									'fas fa-users',      // FontAwesome
									'fas fa-smile',      // FontAwesome
								],

								// Otros
								'otros' => [
									'bi-currency-dollar', // Bootstrap Icons
									'bi-shop',            // Bootstrap Icons
									'fas fa-money-bill-wave', // FontAwesome
									'fas fa-store',           // FontAwesome
								],
							];

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
