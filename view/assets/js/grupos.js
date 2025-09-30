document.addEventListener('DOMContentLoaded', function() {
    const listaGruposContainer = document.getElementById('lista-grupos');
    const nombreGrupoSeleccionado = document.getElementById('nombre-grupo-seleccionado');
    const placeholderDetalle = document.getElementById('placeholder-detalle');
    const contenidoDetalles = document.getElementById('contenido-detalles');
    const listaPermisosContainer = document.getElementById('lista-permisos');
    
    let idGrupoActivo = null;
    let modulosDelSistema = [];

    // --- FUNCIONES ---

    // Función que carga la lista de grupos en la columna izquierda
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

    // Función que carga todos los módulos disponibles y devuelve una promesa
    function cargarModulosDelSistema() {
        return fetch('../../controller/GrupoController.php?action=listarModulos')
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    modulosDelSistema = data.data;
                }
            });
    }
    
    // Función que muestra los detalles de un grupo seleccionado
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
    
    // Función que dibuja los checkboxes de los módulos
    function renderizarCheckboxesDePermisos(permisosActivos) {
        listaPermisosContainer.innerHTML = '';
        if (modulosDelSistema.length === 0) {
            listaPermisosContainer.innerHTML = '<p class="text-red-500">No se pudieron cargar los módulos del sistema.</p>';
            return;
        }

        modulosDelSistema.forEach(modulo => {
            const isChecked = permisosActivos.includes(modulo.id_modulo.toString());
            const checkboxHTML = `
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="modulos[]" value="${modulo.id_modulo}" 
                           class="form-checkbox h-5 w-5 text-blue-600" ${isChecked ? 'checked' : ''}>
                    <span>${modulo.nombre_modulo}</span>
                </label>
            `;
            listaPermisosContainer.innerHTML += checkboxHTML;
        });

        listaPermisosContainer.innerHTML += `
            <div class="col-span-full mt-4">
                <button id="btnGuardarPermisos" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Guardar Cambios
                </button>
            </div>
        `;
    }

    // --- EVENT LISTENERS ---

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

    // --- CARGA INICIAL CORREGIDA ---
    // Primero nos aseguramos de tener la lista de TODOS los módulos,
    // y solo después cargamos los grupos para que el usuario pueda interactuar.
    cargarModulosDelSistema().then(() => {
        cargarGrupos();
    });
});