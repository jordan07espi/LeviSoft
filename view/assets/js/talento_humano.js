document.addEventListener('DOMContentLoaded', function() {
    const tablaBody = document.getElementById('tablaTalentoHumano');
    const btnAdicionar = document.getElementById('btnAdicionar');
    const adicionarDropdown = document.getElementById('adicionarDropdown');

    // Lógica para mostrar/ocultar el dropdown de "Adicionar"
    btnAdicionar.addEventListener('click', () => {
        adicionarDropdown.classList.toggle('hidden');
    });

    // Función para renderizar los grupos como insignias (badges)
    function renderizarGrupos(gruposStr) {
        if (!gruposStr) return '';
        const grupos = gruposStr.split(', ');
        return grupos.map(g => `<span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">${g}</span>`).join('');
    }

    // Función principal para cargar y mostrar los datos
    function cargarPersonal() {
        fetch('../../controller/UsuarioController.php?action=listar')
            .then(res => res.json())
            .then(data => {
                tablaBody.innerHTML = '';
                if (data.success) {
                    data.data.forEach(p => {
                        const fila = `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">${sanitizeHTML(p.nombre_completo)}</td>
                                <td class="p-3">${renderizarGrupos(p.grupos)}</td>
                                <td class="p-3">${sanitizeHTML(p.cedula)}</td>
                                <td class="p-3">
                                    <div class="text-sm">${sanitizeHTML(p.email)}</div>
                                    <div class="text-xs text-gray-500">${sanitizeHTML(p.telefono)}</div>
                                </td>
                                <td class="p-3 text-sm">${sanitizeHTML(p.nombre_sede || '')}</td>
                                <td class="p-3 text-center">
                                    <button class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Acciones</button>
                                </td>
                            </tr>
                        `;
                        tablaBody.innerHTML += fila;
                    });
                }
            });
    }

    // Carga inicial
    cargarPersonal();
});