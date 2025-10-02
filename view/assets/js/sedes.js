document.addEventListener('DOMContentLoaded', function() {
    // --- ELEMENTOS DEL DOM ---
    const tablaBody = document.getElementById('tabla-sedes');
    const btnNueva = document.getElementById('btn-nueva-sede');
    const modal = document.getElementById('sede-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('sede-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    
    const sedeIdInput = document.createElement('input');
    sedeIdInput.type = 'hidden';
    sedeIdInput.name = 'id';
    form.prepend(sedeIdInput);

    const provinciaSelect = document.getElementById('provincia');
    const cantonSelect = document.getElementById('canton');
    const parroquiaSelect = document.getElementById('parroquia');

    // --- FUNCIONES ---

    function cargarTabla() {
        fetch('../../controller/SedeController.php?action=listar')
            .then(res => res.json())
            .then(data => {
                tablaBody.innerHTML = '';
                if (data.success) {
                    data.data.forEach(sede => {
                        tablaBody.innerHTML += `
                            <tr class="border-b text-sm">
                                <td class="p-3">${sede.nombre_sede}</td>
                                <td class="p-3">${sede.codigo_sede}</td>
                                <td class="p-3">${sede.provincia || ''}</td>
                                <td class="p-3">${sede.canton || ''}</td>
                                <td class="p-3">${sede.parroquia || ''}</td>
                                <td class="p-3">${sede.telefonos || ''}</td>
                                <td class="p-3">${sede.direccion || ''}</td>
                                <td class="p-3 text-center">
                                    <div class="relative inline-block text-left dropdown">
                                        <button class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                            Acciones <i class="fas fa-chevron-down text-xs"></i>
                                        </button>
                                        <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 hidden">
                                            <div class="py-1">
                                                <a href="#" class="btn-editar block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="${sede.id_sede}">
                                                    <i class="fas fa-pencil-alt mr-2"></i>Editar
                                                </a>
                                                <a href="#" class="btn-eliminar block px-4 py-2 text-sm text-red-700 hover:bg-red-50" data-id="${sede.id_sede}">
                                                    <i class="fas fa-trash-alt mr-2"></i>Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }
            });
    }
    
    function abrirModal(modo, id = null) {
        form.reset();
        sedeIdInput.value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        if (modo === 'agregar') {
            modalTitle.textContent = 'Nueva Sede';
        } else {
            modalTitle.textContent = 'Editar Sede';
            sedeIdInput.value = id;
            
            const formData = new FormData();
            formData.append('action', 'obtener');
            formData.append('id', id);
            
            fetch('../../controller/SedeController.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const sede = data.data;
                        document.getElementById('nombre').value = sede.nombre_sede;
                        document.getElementById('alias').value = sede.codigo_sede;
                        document.getElementById('telefonos').value = sede.telefonos;
                        document.getElementById('direccion').value = sede.direccion;
                        
                        provinciaSelect.value = sede.id_provincia;
                        provinciaSelect.dispatchEvent(new CustomEvent('change', { detail: { cantonId: sede.id_canton, parroquiaId: sede.id_parroquia } }));
                    }
                });
        }
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- LÓGICA DE SELECTORES ---
    
    fetch('../../controller/SedeController.php?action=listarProvincias')
        .then(res => res.json()).then(data => {
            provinciaSelect.innerHTML = '<option value="">Seleccione...</option>';
            if(data.success) data.data.forEach(p => provinciaSelect.innerHTML += `<option value="${p.id}">${p.provincia}</option>`);
        });

    provinciaSelect.addEventListener('change', function(event) {
        const idProvincia = this.value;
        const detail = event.detail || {};
        cantonSelect.innerHTML = '<option value="">Cargando...</option>';
        parroquiaSelect.innerHTML = '<option value="">--</option>';
        if (!idProvincia) { cantonSelect.innerHTML = '<option value="">--</option>'; return; }
        
        fetch(`../../controller/SedeController.php?action=listarCantones&id_provincia=${idProvincia}`)
            .then(res => res.json()).then(data => {
                cantonSelect.innerHTML = '<option value="">Seleccione...</option>';
                if(data.success) {
                    data.data.forEach(c => cantonSelect.innerHTML += `<option value="${c.id}">${c.canton}</option>`);
                    if (detail.cantonId) {
                        cantonSelect.value = detail.cantonId;
                        cantonSelect.dispatchEvent(new CustomEvent('change', { detail: { parroquiaId: detail.parroquiaId } }));
                    }
                }
            });
    });

    cantonSelect.addEventListener('change', function(event) {
        const idCanton = this.value;
        const detail = event.detail || {};
        parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';
        if (!idCanton) { parroquiaSelect.innerHTML = '<option value="">--</option>'; return; }

        fetch(`../../controller/SedeController.php?action=listarParroquias&id_canton=${idCanton}`)
            .then(res => res.json()).then(data => {
                parroquiaSelect.innerHTML = '<option value="">Seleccione...</option>';
                if(data.success) {
                    data.data.forEach(p => parroquiaSelect.innerHTML += `<option value="${p.id}">${p.parroquia}</option>`);
                    if (detail.parroquiaId) {
                        parroquiaSelect.value = detail.parroquiaId;
                    }
                }
            });
    });

    // --- EVENT LISTENERS ---
    
    btnNueva.addEventListener('click', () => abrirModal('agregar'));
    btnCancelar.addEventListener('click', cerrarModal);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const action = sedeIdInput.value ? 'actualizar' : 'agregar';
        formData.append('action', action);

        fetch('../../controller/SedeController.php', { method: 'POST', body: formData })
            .then(res => res.json()).then(data => {
                alert(data.message);
                if (data.success) {
                    cerrarModal();
                    cargarTabla();
                }
            });
    });

    tablaBody.addEventListener('click', function(e) {
        const dropdown = e.target.closest('.dropdown');

        // Si se hizo clic dentro de un dropdown, manejamos la visibilidad
        if (dropdown) {
            e.preventDefault();
            const menu = dropdown.querySelector('.dropdown-menu');
            const isVisible = !menu.classList.contains('hidden');

            // Cerramos todos los menús abiertos
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));

            // Si el menú no estaba visible, lo mostramos y posicionamos
            if (!isVisible) {
                menu.classList.remove('hidden');
                const buttonRect = dropdown.getBoundingClientRect();
                menu.style.position = 'fixed';
                menu.style.top = `${buttonRect.bottom}px`;
                menu.style.left = `${buttonRect.left}px`;
            }
        }

        const id = e.target.dataset.id;
        if (e.target.classList.contains('btn-editar')) {
            abrirModal('editar', id);
        }
        if (e.target.classList.contains('btn-eliminar')) {
            if (confirm('¿Seguro que deseas eliminar esta sede?')) {
                // ... (lógica fetch para eliminar)
            }
        }
    });

    // Cierra los menús si se hace clic fuera de ellos
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
        }
    });

    cargarTabla();
});