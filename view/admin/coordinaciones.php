<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once '../../helpers/breadcrumb_helper.php';
gestionar_breadcrumb('Coordinaciones', 'coordinaciones.php');
include '../partials/header.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Coordinaciones</h1>
        <button id="btn-nueva" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Nueva Coordinación</button>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Nombre</th><th class="p-3 text-left">Alias</th>
                    <th class="p-3 text-left">Sede</th><th class="p-3 text-left">Responsable</th>
                    <th class="p-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-coordinaciones"></tbody>
        </table>
    </div>
</div>

<div id="coordinacion-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <h2 id="modal-title" class="text-2xl font-bold mb-4"></h2>
        <form id="coordinacion-form">
            <input type="hidden" name="id" id="coordinacion-id">
            <div class="grid grid-cols-2 gap-4">
                <div><label>Nombre</label><input type="text" name="nombre" id="nombre" class="w-full border p-2 rounded" required></div>
                <div><label>Alias</label><input type="text" name="alias" id="alias" class="w-full border p-2 rounded"></div>
                <div><label>Sede</label><select name="id_sede" id="id_sede" class="w-full border p-2 rounded" required></select></div>
                <div>
                    <label>Responsable</label>
                    <div class="flex">
                        <input type="text" id="nombre_responsable" class="w-full border p-2 rounded-l bg-gray-100" readonly>
                        <input type="hidden" name="id_responsable" id="id_responsable">
                        <button type="button" id="btn-buscar-responsable" class="bg-gray-500 text-white p-2 rounded-r">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button type="button" id="btn-cancelar" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="buscar-responsable-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Buscar Responsable</h2>
        <input type="text" id="search-responsable-input" placeholder="Buscar por nombre o cédula..." class="w-full border p-2 rounded mb-4">
        <div id="responsable-results" class="max-h-60 overflow-y-auto"></div>
        <button type="button" id="btn-cancelar-busqueda" class="mt-4 bg-gray-300 px-4 py-2 rounded">Cerrar</button>
    </div>
</div>

<script src="../assets/js/coordinaciones.js"></script>
<?php include '../partials/footer.php'; ?>