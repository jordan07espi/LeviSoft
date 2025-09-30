<?php
// Archivo: view/admin/dashboard.php

// REGLA 1: INICIAR LA SESIÓN PRIMERO, DE FORMA SEGURA.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// REGLA 2: LUEGO, INCLUIR HELPERS Y EJECUTAR LÓGICA.
require_once '../../helpers/breadcrumb_helper.php';
unset($_SESSION['breadcrumb']); 
gestionar_breadcrumb();

// REGLA 3: FINALMENTE, INCLUIR EL HEADER Y MOSTRAR CONTENIDO.
include '../partials/header.php'; 
?>

<div class="space-y-8">

    <div class="bg-white p-4 rounded-lg shadow-md">
        <label for="search-modulos" class="text-sm font-semibold text-gray-600">Búsqueda de Módulos</label>
        <div class="relative mt-2">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="search-modulos" placeholder="Escriba para filtrar..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div id="dashboard-modulos-container" class="space-y-8">
        </div>
</div>

<script src="../assets/js/dashboard.js"></script>

<?php 
include '../partials/footer.php'; 
?>