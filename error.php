<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>🐍nake🐍oftDev - Error</title>
	<!-- Incluye Bootstrap CSS -->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	<!-- Incluye SweetAlert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	body {
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
		background-color: #f8f9fa;
	}

	.error-container {
		text-align: center;
	}

	.error-gif {
		width: 100%;
		max-width: 300px;
		/* Tamaño máximo para dispositivos más grandes */
		height: auto;
	}

	</style>
</head>

<body>
	<div class="error-container">
		<h1 class="display-4">¡Oops! Algo salió mal</h1>
		<p class="lead">Disculpe los inconvenientes, estamos analizando el problema.</p>
		<img id="errorGif" src="" alt="Error GIF" class="error-gif">
	</div>

	<script>
	// Array de URLs de GIFs
	const gifs = ['./giferrors/error_1.gif',
		'./giferrors/error_2.gif',
		'./giferrors/error_3.gif',
		'./giferrors/error_4.gif',
		'./giferrors/error_5.gif',
		'./giferrors/error_6.gif',
		'./giferrors/error_7.gif',
		'./giferrors/error_8.gif',
		'./giferrors/error_9.gif',
		'./giferrors/error_10.gif',
	];

	// Seleccionar un GIF al azar
	const randomGif = gifs[Math.floor(Math.random() * gifs.length)];

	// Establecer el src del elemento img al GIF seleccionado
	document.getElementById('errorGif').src = randomGif;
	</script>
</body>

</html>
