document.addEventListener('DOMContentLoaded', function() {
    const sedeSelect = document.getElementById('sede-select');
    const periodoSelect = document.getElementById('periodo-select');
    const searchInput = document.getElementById('search-modulos'); // Este elemento puede no existir

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

    sedeSelect.addEventListener('change', function() {
        const sedeIdSeleccionada = this.value;
        console.log(`Sede cambiada a: ${sedeIdSeleccionada}. Aquí debes recargar los datos.`);
    });

    periodoSelect.addEventListener('change', function() {
        const periodoIdSeleccionado = this.value;
        console.log(`Periodo cambiado a: ${periodoIdSeleccionado}. Aquí debes recargar los datos.`);
    });

    // CORRECCIÓN: Solo agregar el listener si el elemento de búsqueda existe
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const modulos = document.querySelectorAll('.modulo-card');

            modulos.forEach(modulo => {
                const textoModulo = modulo.textContent.toLowerCase();
                if (textoModulo.includes(filtro)) {
                    modulo.style.display = 'block';
                } else {
                    modulo.style.display = 'none';
                }
            });
        });
    }

    // Carga inicial de datos
    cargarDatosDelHeader();
});