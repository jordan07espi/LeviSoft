<?php
session_start();
include '../partials/header.php'; 
?>

<div class="space-y-8">

    <div class="bg-white p-4 rounded-lg shadow-md">
        <label for="search-modulos" class="text-sm font-semibold text-gray-600">Grupos: Académicos, Administración</label>
        <div class="relative mt-2">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="search-modulos" placeholder="Búsqueda" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Académicos</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-clock fa-3x text-orange-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Horarios</h3>
                <p class="text-xs text-gray-500 mt-1">Configuración de los horarios de clases</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-book fa-3x text-green-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Niveles y materias</h3>
                <p class="text-xs text-gray-500 mt-1">Niveles y materias del periodo actual</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-chalkboard-teacher fa-3x text-yellow-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Clases Impartidas</h3>
                <p class="text-xs text-gray-500 mt-1">Clases Impartidas por los docentes</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-graduation-cap fa-3x text-blue-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Programas</h3>
                <p class="text-xs text-gray-500 mt-1">Gestión de programas que ofertamos</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-sitemap fa-3x text-teal-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Mallas</h3>
                <p class="text-xs text-gray-500 mt-1">Mallas curriculares</p>
            </a>
        </div>
    </div>
    
    <div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Administración</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-search-plus fa-3x text-cyan-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Talento humano</h3>
                <p class="text-xs text-gray-500 mt-1">Talento humano</p>
            </a>
             <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-user-tie fa-3x text-yellow-600 mb-3"></i>
                <h3 class="font-bold text-gray-800">Profesores</h3>
                <p class="text-xs text-gray-500 mt-1">Registro de profesores</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-university fa-3x text-gray-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Coordinaciones</h3>
                <p class="text-xs text-gray-500 mt-1">Registro de coordinaciones</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-calendar-check fa-3x text-red-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Periodos Lectivos</h3>
                <p class="text-xs text-gray-500 mt-1">Administración de periodos lectivos</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-school fa-3x text-indigo-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">Institución</h3>
                <p class="text-xs text-gray-500 mt-1">Datos de la Institución</p>
            </a>
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-id-card fa-3x text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800">Administrativos</h3>
                <p class="text-xs text-gray-500 mt-1">Personal administrativo</p>
            </a>    
        </div>
    </div>

    <div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Más modulos</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <a href="#" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                <i class="fas fa-print fa-3x text-gray-600 mb-3"></i>
                <h3 class="font-bold text-gray-800">Reportes</h3>
                <p class="text-xs text-gray-500 mt-1">Listado de reportes</p>
            </a>
        </div>
    </div>

</div>

<script src="../assets/js/app.js"></script>

<?php 
include '../partials/footer.php'; 
?>