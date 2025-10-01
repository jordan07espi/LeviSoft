document.addEventListener('DOMContentLoaded', function() {
    // --- ELEMENTOS DEL DOM ---
    const tablaBody = document.getElementById('tabla-coordinaciones');
    const btnNueva = document.getElementById('btn-nueva');
    
    // Modal principal
    const modal = document.getElementById('coordinacion-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('coordinacion-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    const coordinacionIdInput = document.getElementById('coordinacion-id');

    // Modal de búsqueda de responsable
    const buscarResponsableModal = document.getElementById('buscar-responsable-modal');
    const btnBuscarResponsable = document.getElementById('btn-buscar-responsable');
    const btnCancelarBusqueda = document.getElementById('btn-cancelar-busqueda');
    const searchResponsableInput = document.getElementById('search-responsable-input');
    const responsableResultsContainer = document.getElementById('responsable-results');
    
    let sedes = [];
    let coordinacionIdParaAsignar = null; // Para saber a qué fila asignar el responsable desde la tabla

    // --- FUNCIONES ---

    async function inicializar() {
        await cargarSedes();
        cargarCoordinaciones();
    }

    async function cargarSedes() {
        try {
            const response = await fetch('../../controller/CoordinacionController.php?action=listarSedes');
            const data = await response.json();
            if (data.success) {
                sedes = data.data;
                const sedeSelect = document.getElementById('id_sede');
                sedeSelect.innerHTML = '<option value="">-- Seleccione Sede --</option>';
                sedes.forEach(sede => {
                    sedeSelect.innerHTML += `<option value="${sede.id_sede}">${sede.nombre_sede}</option>`;
                });
            }
        } catch (error) {
            console.error('Error cargando sedes:', error);
        }
    }

    function cargarCoordinaciones() {
        fetch('../../controller/CoordinacionController.php?action=listar')
            .then(res => res.json())
            .then(data => {
                tablaBody.innerHTML = '';
                if (data.success) {
                    data.data.forEach(coord => {
                        let responsableHtml = '';
                        if (coord.nombre_responsable) {
                            responsableHtml = `<span>${coord.nombre_responsable}</span>`;
                        } else {
                            responsableHtml = `<button class="btn-agregar-responsable bg-green-500 text-white px-3 py-1 text-sm rounded" data-id="${coord.id_coordinacion}">Agregar</button>`;
                        }

                        const fila = `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-semibold">${coord.nombre_coordinacion}</td>
                                <td class="p-3">${coord.alias_coordinacion}</td>
                                <td class="p-3">${coord.nombre_sede}</td>
                                <td class="p-3">${responsableHtml}</td>
                                <td class="p-3 text-center">
                                    <button class="btn-editar bg-yellow-500 text-white px-3 py-1 rounded" data-id="${coord.id_coordinacion}">Editar</button>
                                    <button class="btn-eliminar bg-red-500 text-white px-3 py-1 rounded" data-id="${coord.id_coordinacion}">Eliminar</button>
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
        document.getElementById('nombre_responsable').value = ''; // Limpiar campo de texto del responsable
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
                        document.getElementById('alias').value = coordinacion.alias_coordinacion;
                        document.getElementById('id_sede').value = coordinacion.id_sede;
                        document.getElementById('id_responsable').value = coordinacion.id_responsable;
                        // Aquí necesitaremos una función extra para buscar el nombre del responsable y ponerlo en el input de texto
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

    btnNueva.addEventListener('click', () => {
        coordinacionIdParaAsignar = null; // Asegurarse de que no esté activo el modo de asignación directa
        abrirModal('agregar');
    });
    btnCancelar.addEventListener('click', cerrarModal);
    btnBuscarResponsable.addEventListener('click', abrirModalBusqueda);
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
            const nombreResponsable = e.target.dataset.nombre;
            cerrarModalBusqueda();
            
            if (coordinacionIdParaAsignar) { // Si venimos desde el botón "Agregar" de la tabla
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
            } else { // Si venimos desde el formulario modal principal
                document.getElementById('id_responsable').value = idResponsable;
                document.getElementById('nombre_responsable').value = nombreResponsable;
            }
        }
    });

    tablaBody.addEventListener('click', function(e) {
        const id = e.target.dataset.id;
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
            coordinacionIdParaAsignar = id; // Guardamos el ID de la coordinación
            abrirModalBusqueda();
        }
    });

    inicializar();
});