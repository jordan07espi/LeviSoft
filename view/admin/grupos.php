<?php
// Archivo: view/admin/grupos.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../helpers/breadcrumb_helper.php';
gestionar_breadcrumb('Grupos y Permisos', 'grupos.php');
include '../partials/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Grupos</h2>
        <div id="lista-grupos" class="space-y-2">
            <p class="text-gray-500">Cargando grupos...</p>
        </div>
    </div>

    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <div id="detalle-grupo-container">
            <h2 id="nombre-grupo-seleccionado" class="text-2xl font-bold">Seleccione un grupo</h2>
            <p id="placeholder-detalle" class="text-gray-600 mt-2">Aquí podrá ver los usuarios y permisos del grupo seleccionado.</p>

            <div id="contenido-detalles" class="hidden space-y-6 mt-4">
                <div>
                    <h3 class="text-lg font-semibold border-b pb-2">Módulos Permitidos</h3>
                    <div id="lista-permisos" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                        </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold border-b pb-2">Usuarios en este Grupo</h3>
                    <div id="lista-usuarios-grupo" class="mt-4 space-y-2"> </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/grupos.js"></script>

<?php include '../partials/footer.php'; ?>