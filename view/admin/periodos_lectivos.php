<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once '../../helpers/breadcrumb_helper.php';
gestionar_breadcrumb('Períodos Lectivos', 'periodos_lectivos.php');
include '../partials/header.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Períodos Lectivos</h1>
        <button id="btn-nuevo-periodo" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Nuevo Período</button>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200 text-left text-sm font-semibold">
                    <th class="p-3">Nombre del Período</th>
                    <th class="p-3">Fecha de Inicio</th>
                    <th class="p-3">Fecha de Fin</th>
                    <th class="p-3 text-center w-32"></th> </tr>
            </thead>
            <tbody id="tabla-periodos"></tbody>
        </table>
    </div>
</div>

<div id="periodo-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <h2 id="modal-title" class="text-2xl font-bold mb-4"></h2>
        <form id="periodo-form">
            <input type="hidden" name="id" id="periodo-id">
            <div class="space-y-4">
                <div>
                    <label for="nombre">Nombre del Período</label>
                    <input type="text" name="nombre" id="nombre" class="w-full border p-2 rounded mt-1" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label for="fecha_inicio">Fecha Inicio</label><input type="date" name="fecha_inicio" id="fecha_inicio" class="w-full border p-2 rounded mt-1" required></div>
                    <div><label for="fecha_fin">Fecha Fin</label><input type="date" name="fecha_fin" id="fecha_fin" class="w-full border p-2 rounded mt-1" required></div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button type="button" id="btn-cancelar" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/periodos_lectivos.js"></script>
<?php include '../partials/footer.php'; ?>