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
                                    <button class="btn-editar bg-yellow-500 text-white px-2 py-1 rounded" data-id="${sede.id_sede}">Editar</button>
                                    <button class="btn-eliminar bg-red-500 text-white px-2 py-1 rounded" data-id="${sede.id_sede}">Eliminar</button>
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
        const id = e.target.dataset.id;
        if (e.target.classList.contains('btn-editar')) {
            abrirModal('editar', id);
        }
        if (e.target.classList.contains('btn-eliminar')) {
            if (confirm('¿Seguro que deseas eliminar esta sede?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('id', id);
                fetch('../../controller/SedeController.php', { method: 'POST', body: formData })
                    .then(res => res.json()).then(data => {
                        alert(data.message);
                        if(data.success) cargarTabla();
                    });
            }
        }
    });

    cargarTabla();
});