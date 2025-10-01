document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('dashboard-modulos-container');
    const searchInput = document.getElementById('search-modulos');

    function crearModuloCard(modulo) {
        const defaultIcon = 'fas fa-puzzle-piece';
        const icon = modulo.icono_fa || defaultIcon;
        return `
            <a href="${modulo.url}" class="modulo-card bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center flex flex-col justify-center items-center">
                <i class="${icon} fa-3x text-blue-500 mb-3"></i>
                <h3 class="font-bold text-gray-800">${modulo.nombre_modulo}</h3>
                <p class="text-xs text-gray-500 mt-1">${modulo.descripcion || ''}</p>
            </a>
        `;
    }

    function cargarModulos() {
        container.innerHTML = '<p class="text-center text-gray-500">Cargando módulos permitidos...</p>';
        fetch('../../controller/AppController.php?action=cargarModulosDashboard')
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    const modulosAgrupados = data.data.reduce((acc, modulo) => {
                        (acc[modulo.categoria] = acc[modulo.categoria] || []).push(modulo);
                        return acc;
                    }, {});

                    for (const categoria in modulosAgrupados) {
                        const modulosHTML = modulosAgrupados[categoria].map(crearModuloCard).join('');
                        container.innerHTML += `
                            <div>
                                <h2 class="text-2xl font-bold text-gray-700 mb-4">${categoria}</h2>
                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                                    ${modulosHTML}
                                </div>
                            </div>
                        `;
                    }
                } else {
                    container.innerHTML = '<p class="text-center text-red-500">No tienes acceso a ningún módulo o ha ocurrido un error.</p>';
                }
            })
            .catch(error => {
                console.error('Error al cargar módulos:', error);
                container.innerHTML = '<p class="text-center text-red-500">Error de conexión al intentar cargar los módulos.</p>';
            });
    }

    // --- LÓGICA DE BÚSQUEDA CORREGIDA ---
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase().trim();
            const modulos = document.querySelectorAll('.modulo-card');
            
            modulos.forEach(modulo => {
                const textoModulo = modulo.textContent.toLowerCase();
                // Si el texto del módulo incluye el filtro, lo mostramos ('flex'). Si no, lo ocultamos ('none').
                if (textoModulo.includes(filtro)) {
                    modulo.style.display = 'flex';
                } else {
                    modulo.style.display = 'none';
                }
            });
        });
    }

    cargarModulos();
});