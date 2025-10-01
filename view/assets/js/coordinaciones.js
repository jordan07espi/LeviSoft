document.addEventListener('DOMContentLoaded', function() {
    // --- ELEMENTOS DEL DOM ---
    const tablaBody = document.getElementById('tabla-coordinaciones');
    const btnNueva = document.getElementById('btn-nueva');
    
    const modal = document.getElementById('coordinacion-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('coordinacion-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    const coordinacionIdInput = document.getElementById('coordinacion-id');
    const aliasInput = document.getElementById('alias');
    const sedeSelect = document.getElementById('id_sede');

    const buscarResponsableModal = document.getElementById('buscar-responsable-modal');
    const btnCancelarBusqueda = document.getElementById('btn-cancelar-busqueda');
    const searchResponsableInput = document.getElementById('search-responsable-input');
    const responsableResultsContainer = document.getElementById('responsable-results');
    
    let sedesData = [];
    let coordinacionIdParaAsignar = null;

    // --- FUNCIONES ---

    async function inicializar() {
        await cargarSedes();
        cargarCoordinaciones();
    }

    // --- FUNCIÓN CORREGIDA ---
    async function cargarSedes() {
        try {
            // CORRECCIÓN: Apuntamos al controlador de Sedes para obtener la lista de sedes.
            const response = await fetch('../../controller/SedeController.php?action=listar');
            const data = await response.json();
            if (data.success) {
                sedesData = data.data;
                sedeSelect.innerHTML = '<option value="">-- Seleccione Sede --</option>';
                sedesData.forEach(sede => {
                    // Usamos nombre_sede y id_sede que vienen del SedeController
                    sedeSelect.innerHTML += `<option value="${sede.id_sede}">${sede.nombre_sede}</option>`;
                });
            }
        } catch (error) {
            console.error('Error cargando sedes:', error);
        }
    }
    // --- FIN DE LA CORRECCIÓN ---

    function cargarCoordinaciones() {
        fetch('../../controller/CoordinacionController.php?action=listar')
            .then(res => res.json())
            .then(data => {
                tablaBody.innerHTML = '';
                if (data.success) {
                    data.data.forEach(coord => {
                        // --- LÓGICA DEL BOTÓN DE ACCIONES ---
                        let quitarResponsableOpcion = '';
                        if (coord.id_responsable) {
                            // Solo muestra esta opción si hay un responsable asignado
                            quitarResponsableOpcion = `
                                <a href="#" class="btn-quitar-responsable block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="${coord.id_coordinacion}">
                                    <i class="fas fa-user-slash mr-2"></i>Quitar Responsable
                                </a>
                            `;
                        }

                        let responsableHtml = coord.nombre_responsable 
                            ? `<span>${coord.nombre_responsable}</span>`
                            : `<button class="btn-agregar-responsable bg-green-500 text-white px-3 py-1 text-sm rounded" data-id="${coord.id_coordinacion}">Agregar</button>`;

                        const fila = `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-semibold">${coord.nombre_coordinacion}</td>
                                <td class="p-3">${coord.alias_coordinacion}</td>
                                <td class="p-3">${coord.nombre_sede}</td>
                                <td class="p-3">${responsableHtml}</td>
                                <td class="p-3 text-center">
                                    <div class="relative inline-block text-left dropdown">
                                        <button class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                            Acciones <i class="fas fa-chevron-down text-xs"></i>
                                        </button>
                                        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 hidden dropdown-menu">
                                            <div class="py-1">
                                                <a href="#" class="btn-editar block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="${coord.id_coordinacion}">
                                                    <i class="fas fa-pencil-alt mr-2"></i>Editar
                                                </a>
                                                ${quitarResponsableOpcion}
                                                <a href="#" class="btn-eliminar block px-4 py-2 text-sm text-red-700 hover:bg-red-50" data-id="${coord.id_coordinacion}">
                                                    <i class="fas fa-trash-alt mr-2"></i>Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tablaBody.innerHTML += fila;
                    });
                }
            });
    }

    function abrirModal(modo, id = null) {
        form.reset();
        coordinacionIdInput.value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        if (modo === 'agregar') {
            modalTitle.textContent = 'Nueva Coordinación';
        } else {
            modalTitle.textContent = 'Editar Coordinación';
            coordinacionIdInput.value = id;
            
            const formData = new FormData();
            formData.append('action', 'obtener');
            formData.append('id', id);
            fetch('../../controller/CoordinacionController.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const coordinacion = data.data;
                        document.getElementById('nombre').value = coordinacion.nombre_coordinacion;
                        sedeSelect.value = coordinacion.id_sede;
                        sedeSelect.dispatchEvent(new Event('change'));
                    }
                });
        }
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function abrirModalBusqueda() {
        searchResponsableInput.value = '';
        responsableResultsContainer.innerHTML = '';
        buscarResponsableModal.classList.remove('hidden');
        buscarResponsableModal.classList.add('flex');
    }

    function cerrarModalBusqueda() {
        buscarResponsableModal.classList.add('hidden');
        buscarResponsableModal.classList.remove('flex');
    }

    // --- EVENT LISTENERS ---

    sedeSelect.addEventListener('change', function() {
        const selectedSedeId = this.value;
        const sedeEncontrada = sedesData.find(sede => sede.id_sede == selectedSedeId);
        if (sedeEncontrada) {
            aliasInput.value = sedeEncontrada.codigo_sede;
        } else {
            aliasInput.value = '';
        }
    });

    btnNueva.addEventListener('click', () => {
        coordinacionIdParaAsignar = null;
        abrirModal('agregar');
    });
    btnCancelar.addEventListener('click', cerrarModal);
    btnCancelarBusqueda.addEventListener('click', cerrarModalBusqueda);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const action = coordinacionIdInput.value ? 'actualizar' : 'agregar';
        formData.append('action', action);

        fetch('../../controller/CoordinacionController.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    cerrarModal();
                    cargarCoordinaciones();
                }
            });
    });
    
    searchResponsableInput.addEventListener('keyup', function() {
        const termino = this.value;
        if (termino.length < 2) {
            responsableResultsContainer.innerHTML = '';
            return;
        }
        fetch(`../../controller/UsuarioController.php?action=buscar&termino=${termino}`)
            .then(res => res.json())
            .then(data => {
                responsableResultsContainer.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    data.data.forEach(user => {
                        const item = document.createElement('div');
                        item.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                        item.textContent = `${user.nombre_completo} (${user.cedula})`;
                        item.dataset.id = user.id_usuario;
                        item.dataset.nombre = user.nombre_completo;
                        responsableResultsContainer.appendChild(item);
                    });
                } else {
                    responsableResultsContainer.innerHTML = '<p class="p-2 text-gray-500">No se encontraron resultados.</p>';
                }
            });
    });

    responsableResultsContainer.addEventListener('click', function(e) {
        if (e.target.dataset.id) {
            const idResponsable = e.target.dataset.id;
            cerrarModalBusqueda();
            
            if (coordinacionIdParaAsignar) {
                const formData = new FormData();
                formData.append('action', 'asignarResponsable');
                formData.append('id_coordinacion', coordinacionIdParaAsignar);
                formData.append('id_responsable', idResponsable);

                fetch('../../controller/CoordinacionController.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) cargarCoordinaciones();
                        alert(data.message);
                    });
                coordinacionIdParaAsignar = null;
            }
        }
    });

    tablaBody.addEventListener('click', function(e) {
        e.preventDefault();
        const id = e.target.dataset.id;
        // Lógica para mostrar/ocultar el menú
        const dropdown = e.target.closest('.dropdown');
        if (dropdown) {
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
        if (e.target.classList.contains('btn-editar')) {
            coordinacionIdParaAsignar = null;
            abrirModal('editar', id);
        }
        if (e.target.classList.contains('btn-eliminar')) {
            if (confirm('¿Estás seguro de que deseas eliminar esta coordinación?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('id', id);
                fetch('../../controller/CoordinacionController.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if(data.success) cargarCoordinaciones();
                    });
            }
        }
        if (e.target.classList.contains('btn-agregar-responsable')) {
            coordinacionIdParaAsignar = id;
            abrirModalBusqueda();
        }

        // --- NUEVO LISTENER PARA QUITAR RESPONSABLE ---
        if (e.target.classList.contains('btn-quitar-responsable')) {
            if (confirm('¿Estás seguro de que deseas quitar al responsable de esta coordinación?')) {
                const formData = new FormData();
                formData.append('action', 'quitarResponsable');
                formData.append('id', id);
                fetch('../../controller/CoordinacionController.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            cargarCoordinaciones();
                        }
                    });
            }
        }

        
    });
    // Cierra los menús si se hace clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    inicializar();
});