document.addEventListener('DOMContentLoaded', function() {
    const sedeSelect = document.getElementById('sede-select');
    const periodoSelect = document.getElementById('periodo-select');
    const searchInput = document.getElementById('search-modulos');

    // Función para cargar los datos iniciales del header (sedes y periodos)
    function cargarDatosDelHeader() {
        fetch('../../controller/AppController.php?action=cargarDatosHeader')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Poblar selector de sedes
                    sedeSelect.innerHTML = '';
                    data.data.sedes.forEach(sede => {
                        const option = document.createElement('option');
                        option.value = sede.id_sede;
                        option.textContent = `${sede.codigo_sede} - ${sede.nombre_sede}`;
                        sedeSelect.appendChild(option);
                    });

                    // Poblar selector de periodos
                    periodoSelect.innerHTML = '';
                    data.data.periodos.forEach(periodo => {
                        const option = document.createElement('option');
                        option.value = periodo.id_periodo;
                        option.textContent = periodo.nombre_periodo;
                        periodoSelect.appendChild(option);
                    });
                } else {
                    console.error('Error al cargar datos del header:', data.message);
                }
            })
            .catch(error => console.error('Error en fetch:', error));
    }

    // --- LÓGICA DE EVENT LISTENERS ---

    // Cuando el usuario cambia la sede, deberás recargar la info de la página
    sedeSelect.addEventListener('change', function() {
        const sedeIdSeleccionada = this.value;
        console.log(`Sede cambiada a: ${sedeIdSeleccionada}. Aquí debes recargar los datos.`);
        // Aquí iría la lógica para refrescar los módulos o datos del cuerpo de la página
    });

    // Lo mismo para el periodo
    periodoSelect.addEventListener('change', function() {
        const periodoIdSeleccionado = this.value;
        console.log(`Periodo cambiado a: ${periodoIdSeleccionado}. Aquí debes recargar los datos.`);
    });

    // Lógica de búsqueda/filtrado de módulos
    searchInput.addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();
        const modulos = document.querySelectorAll('.modulo-card');

        modulos.forEach(modulo => {
            // Busca en el título y la descripción del módulo
            const textoModulo = modulo.textContent.toLowerCase();
            if (textoModulo.includes(filtro)) {
                modulo.style.display = 'block';
            } else {
                modulo.style.display = 'none';
            }
        });
    });

    // Carga inicial de datos
    cargarDatosDelHeader();
});