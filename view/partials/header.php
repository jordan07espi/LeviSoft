<?php
// Archivo: view/partials/header.php

// 1. INICIO DE SESIÓN SEGURO
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. CONTROL DE CACHÉ DEL NAVEGADOR
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// 3. VERIFICACIÓN DE SESIÓN Y TIEMPO DE INACTIVIDAD
$tiempo_limite_inactividad = 30 * 60; // 30 minutos

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../login.php?error=no_session');
    exit();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $tiempo_limite_inactividad)) {
    session_unset();
    session_destroy();
    header('Location: ../../login.php?error=inactive');
    exit();
}

$_SESSION['last_activity'] = time();

$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Usuario';
$rolUsuario = $_SESSION['rol'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeviSoft - Horarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <header class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto p-4 flex flex-col md:flex-row md:items-center md:justify-between">

            <div class="flex items-center justify-between w-full">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold">LeviSoft</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span id="nombre-usuario-header" class="inline font-semibold"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="../../controller/logout.php" class="bg-red-600 px-3 py-2 rounded hover:bg-red-700" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mt-4 md:mt-0 md:ml-6">
                <select id="sede-select" name="sede" class="w-full sm:w-auto bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                    </select>

                <select id="periodo-select" name="periodo" class="w-full sm:w-auto bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                    </select>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4 flex-grow">