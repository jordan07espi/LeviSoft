document.addEventListener('DOMContentLoaded', function() {
    const tablaBody = document.getElementById('tabla-periodos');
    const btnNuevo = document.getElementById('btn-nuevo-periodo');
    const modal = document.getElementById('periodo-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('periodo-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    const periodoIdInput = document.getElementById('periodo-id');

    function cargarTabla() {
        fetch('../../controller/PeriodoController.php?action=listar')
            .then(res => res.json())
            .then(data => {
                tablaBody.innerHTML = '';
                if (data.success) {
                    data.data.forEach(periodo => {
                        tablaBody.innerHTML += `
                            <tr class="border-b text-sm">
                                <td class="p-3">${periodo.nombre_periodo}</td>
                                <td class="p-3">${periodo.fecha_inicio}</td>
                                <td class="p-3">${periodo.fecha_fin}</td>
                                <td class="p-3 text-center">
                                    <div class="relative inline-block text-left dropdown">
                                        <button class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Acciones <i class="fas fa-chevron-down text-xs"></i></button>
                                        <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 hidden">
                                            <div class="py-1">
                                                <a href="#" class="btn-editar block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="${periodo.id_periodo}"><i class="fas fa-pencil-alt mr-2"></i>Editar</a>
                                                <a href="#" class="btn-eliminar block px-4 py-2 text-sm text-red-700 hover:bg-red-50" data-id="${periodo.id_periodo}"><i class="fas fa-trash-alt mr-2"></i>Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    });
                }
            });
    }

    function abrirModal(modo, id = null) {
        form.reset();
        periodoIdInput.value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (modo === 'agregar') {
            modalTitle.textContent = 'Nuevo Período Lectivo';
        } else {
            modalTitle.textContent = 'Editar Período Lectivo';
            periodoIdInput.value = id;
            const formData = new FormData();
            formData.append('action', 'obtener');
            formData.append('id', id);

            fetch('../../controller/PeriodoController.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('nombre').value = data.data.nombre_periodo;
                        document.getElementById('fecha_inicio').value = data.data.fecha_inicio;
                        document.getElementById('fecha_fin').value = data.data.fecha_fin;
                    }
                });
        }
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    btnNuevo.addEventListener('click', () => abrirModal('agregar'));
    btnCancelar.addEventListener('click', cerrarModal);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const action = periodoIdInput.value ? 'actualizar' : 'agregar';
        formData.append('action', action);
        formData.set('id', periodoIdInput.value);

        fetch('../../controller/PeriodoController.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    cerrarModal();
                    cargarTabla();
                    window.refrescarHeader();
                }
            });
    });

    // --- LISTENER DE LA TABLA ACTUALIZADO ---
    tablaBody.addEventListener('click', function(e) {
        e.preventDefault();
        
        const dropdown = e.target.closest('.dropdown');
        if (dropdown) {
            const menu = dropdown.querySelector('.dropdown-menu');
            const isVisible = !menu.classList.contains('hidden');

            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));

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
            if (confirm('¿Estás seguro de que deseas eliminar este período?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('id', id);
                fetch('../../controller/PeriodoController.php', { method: 'POST', body: formData })
                    .then(res => res.json()).then(data => {
                        alert(data.message);
                        if(data.success) {
                            cargarTabla();
                            window.refrescarHeader();
                        }
                    });
            }
        }
    });

    // Cierra los menús si se hace clic fuera
    document.addEventListener('click', e => {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
        }
    });

    cargarTabla();
});