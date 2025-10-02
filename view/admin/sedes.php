<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once '../../helpers/breadcrumb_helper.php';
gestionar_breadcrumb('Sedes', 'sedes.php');
include '../partials/header.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Sedes</h1>
        <button id="btn-nueva-sede" class="bg-green-600 text-white px-4 py-2 rounded-lg">Nueva Sede</button>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200 text-left text-sm font-semibold">
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Alias</th>
                    <th class="p-3">Provincia</th>
                    <th class="p-3">Cantón</th>
                    <th class="p-3">Parroquia</th>
                    <th class="p-3">Teléfonos</th>
                    <th class="p-3">Dirección</th>
                    <th class="p-3 text-center w-32"></th> </tr>
            </thead>
            <tbody id="tabla-sedes"></tbody>
        </table>
    </div>
</div>

<div id="sede-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl">
        <h2 id="modal-title" class="text-2xl font-bold mb-4"></h2>
        <form id="sede-form">
            <div class="grid grid-cols-2 gap-4">
                <div><label>Nombre</label><input type="text" name="nombre" id="nombre" class="w-full border p-2 rounded" required></div>
                <div><label>Alias</label><input type="text" name="alias" id="alias" class="w-full border p-2 rounded"></div>
                <div><label>Provincia</label><select id="provincia" name="id_provincia" class="w-full border p-2 rounded"></select></div>
                <div><label>Cantón</label><select id="canton" name="id_canton" class="w-full border p-2 rounded"></select></div>
                <div><label>Parroquia</label><select id="parroquia" name="id_parroquia" class="w-full border p-2 rounded"></select></div>
                <div><label>Teléfonos</label><input type="text" name="telefonos" id="telefonos" class="w-full border p-2 rounded"></div>
                <div class="col-span-2"><label>Dirección</label><textarea name="direccion" id="direccion" class="w-full border p-2 rounded"></textarea></div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button type="button" id="btn-cancelar" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/sedes.js"></script>
<?php include '../partials/footer.php'; ?>