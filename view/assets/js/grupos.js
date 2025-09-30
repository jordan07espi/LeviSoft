document.addEventListener('DOMContentLoaded', function() {
    const listaGruposContainer = document.getElementById('lista-grupos');
    const nombreGrupoSeleccionado = document.getElementById('nombre-grupo-seleccionado');
    const placeholderDetalle = document.getElementById('placeholder-detalle');
    const contenidoDetalles = document.getElementById('contenido-detalles');
    const listaPermisosContainer = document.getElementById('lista-permisos');
    
    let idGrupoActivo = null;
    let modulosDelSistema = [];

    // --- FUNCIONES ---

    function cargarGrupos() {
        fetch('../../controller/GrupoController.php?action=listarGrupos')
            .then(res => res.json())
            .then(data => {
                listaGruposContainer.innerHTML = '';
                if (data.success) {
                    data.data.forEach(grupo => {
                        const grupoElement = document.createElement('a');
                        grupoElement.href = '#';
                        grupoElement.className = 'block p-3 rounded-md hover:bg-gray-100 cursor-pointer grupo-item';
                        grupoElement.textContent = grupo.nombre_grupo;
                        grupoElement.dataset.id = grupo.id_grupo;
                        listaGruposContainer.appendChild(grupoElement);
                    });
                }
            });
    }

    function cargarModulosDelSistema() {
        return fetch('../../controller/GrupoController.php?action=listarModulos')
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    modulosDelSistema = data.data;
                }
            });
    }
    
    function mostrarDetallesDeGrupo(idGrupo, nombreGrupo) {
        idGrupoActivo = idGrupo;
        nombreGrupoSeleccionado.textContent = `Permisos para: ${nombreGrupo}`;
        placeholderDetalle.classList.add('hidden');
        contenidoDetalles.classList.remove('hidden');
        listaPermisosContainer.innerHTML = '<p>Cargando permisos...</p>';

        fetch(`../../controller/GrupoController.php?action=obtenerPermisos&id_grupo=${idGrupo}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const permisosActivos = data.data;
                    renderizarCheckboxesDePermisos(permisosActivos);
                }
            });
        
        cargarUsuariosDelGrupo(idGrupo);
    }

    function cargarUsuariosDelGrupo(idGrupo) {
        const container = document.getElementById('lista-usuarios-grupo');
        container.innerHTML = '<p class="text-gray-500">Cargando usuarios...</p>';

        fetch(`../../controller/GrupoController.php?action=listarUsuariosPorGrupo&id_grupo=${idGrupo}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    data.data.forEach(usuario => {
                        container.innerHTML += `<div class="p-2 border-b">${usuario.nombre_completo}</div>`;
                    });
                } else {
                    container.innerHTML = '<p class="text-gray-500">No hay usuarios en este grupo.</p>';
                }
            });
    }
    
    // --- FUNCIÓN CORREGIDA ---
    function renderizarCheckboxesDePermisos(permisosActivos) {
        let contentHTML = '';
        if (modulosDelSistema.length === 0) {
            listaPermisosContainer.innerHTML = '<p class="text-red-500">No se pudieron cargar los módulos.</p>';
            return;
        }

        // Usamos un Set para una búsqueda mucho más rápida y segura
        const permisosSet = new Set(permisosActivos.map(String));

        modulosDelSistema.forEach(modulo => {
            // Comparamos usando el Set, asegurando que ambos sean strings
            const isChecked = permisosSet.has(String(modulo.id_modulo));
            contentHTML += `
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="modulos[]" value="${modulo.id_modulo}" 
                           class="form-checkbox h-5 w-5 text-blue-600" ${isChecked ? 'checked' : ''}>
                    <span>${modulo.nombre_modulo}</span>
                </label>
            `;
        });

        contentHTML += `
            <div class="col-span-full mt-4">
                <button id="btnGuardarPermisos" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Guardar Cambios
                </button>
            </div>
        `;
        
        // Asignamos todo el contenido de una sola vez para mayor eficiencia
        listaPermisosContainer.innerHTML = contentHTML;
    }

    // --- EVENT LISTENERS (Sin cambios) ---
    listaGruposContainer.addEventListener('click', function(e) {
        e.preventDefault();
        if (e.target.classList.contains('grupo-item')) {
            document.querySelectorAll('.grupo-item').forEach(el => el.classList.remove('bg-blue-100', 'font-bold'));
            e.target.classList.add('bg-blue-100', 'font-bold');
            
            const id = e.target.dataset.id;
            const nombre = e.target.textContent;
            mostrarDetallesDeGrupo(id, nombre);
        }
    });

    contenidoDetalles.addEventListener('click', function(e){
        if(e.target.id === 'btnGuardarPermisos'){
            const form = new FormData();
            form.append('action', 'guardarPermisos');
            form.append('id_grupo', idGrupoActivo);

            document.querySelectorAll('input[name="modulos[]"]:checked').forEach(checkbox => {
                form.append('modulos[]', checkbox.value);
            });

            fetch('../../controller/GrupoController.php', { method: 'POST', body: form })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Error desconocido.');
                });
        }
    });

    // --- CARGA INICIAL (Sin cambios) ---
    cargarModulosDelSistema().then(() => {
        cargarGrupos();
    });
});