<?php
define("DEVELOPER", false);
// BASE DE DATOS
if (DEVELOPER) {
	define("DB_HOST", "localhost");
	define("DB_NAME", "qr_menu");
	define("DB_USER_NAME", "root");
	define("DB_USER_PASSWORD", "drowssap");
} else {
	define("DB_HOST", "localhost");
	define("DB_NAME", "c2681170_qr_menu");
	define("DB_USER_NAME", "c2681170_qr_menu");
	define("DB_USER_PASSWORD", "kuweVAro54");
}

// RUTAS
// URI
if (DEVELOPER) {
	define("URI", "http://localhost/menu/");
	define("URI_SIN_HOST", "/menu/");
}
