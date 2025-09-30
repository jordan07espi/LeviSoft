<?php
// Archivo: view/admin/talento_humano.php

// REGLA 1: INICIAR LA SESIÓN PRIMERO, DE FORMA SEGURA.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// REGLA 2: LUEGO, INCLUIR HELPERS Y EJECUTAR LÓGICA.
require_once '../../helpers/breadcrumb_helper.php';
gestionar_breadcrumb('Talento Humano', 'talento_humano.php');

// REGLA 3: FINALMENTE, INCLUIR EL HEADER Y MOSTRAR CONTENIDO.
include '../partials/header.php';
?>

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-3xl font-bold text-gray-800">Talento Humano</h1>
        <div class="flex items-center gap-2">
            <div class="relative inline-block text-left">
                <button id="btnAdicionar" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Adicionar
                </button>
                <div id="adicionarDropdown" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profesor</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Administrativo</a>
                    </div>
                </div>
            </div>
            <input type="text" id="searchInput" placeholder="Buscar por cédula o nombre..." class="border rounded-lg px-3 py-2">
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Grupos/Departamento</th>
                    <th class="p-3 text-left">Identificación</th>
                    <th class="p-3 text-left">Email / Teléfono</th>
                    <th class="p-3 text-left">Datos</th>
                    <th class="p-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaTalentoHumano">
                </tbody>
        </table>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
<script src="../assets/js/talento_humano.js"></script>