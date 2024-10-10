<?php
function mostrarDataProductos($empresa)
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
