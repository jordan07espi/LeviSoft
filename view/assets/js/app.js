document.addEventListener('DOMContentLoaded', function() {
    // El ID del select en el HTML sigue siendo 'sede-select', no es necesario cambiarlo.
    const sedeSelect = document.getElementById('sede-select');
    const periodoSelect = document.getElementById('periodo-select');
    
    function cargarDatosDelHeader() {
        fetch('../../controller/AppController.php?action=cargarDatosHeader')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // --- INICIO DE LA MODIFICACIÓN ---
                    // Ahora poblamos el selector con los datos de 'coordinaciones'
                    sedeSelect.innerHTML = '';
                    if (data.data.coordinaciones && data.data.coordinaciones.length > 0) {
                        data.data.coordinaciones.forEach(coord => {
                            const option = document.createElement('option');
                            // Usamos los nuevos campos de la tabla coordinaciones
                            option.value = coord.id_coordinacion;
                            option.textContent = `${coord.alias_coordinacion} - ${coord.nombre_coordinacion}`;
                            sedeSelect.appendChild(option);
                        });
                    } else {
                        // Mensaje por si no hay coordinaciones creadas
                        sedeSelect.innerHTML = '<option value="">No hay coordinaciones</option>';
                    }
                    // --- FIN DE LA MODIFICACIÓN ---

                    // Poblar selector de periodos (esto no cambia)
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

    // --- LÓGICA DE EVENT LISTENERS (No cambia) ---
    if(sedeSelect) {
        sedeSelect.addEventListener('change', function() {
            const coordinacionIdSeleccionada = this.value;
            console.log(`Coordinación cambiada a: ${coordinacionIdSeleccionada}.`);
        });
    }

    if(periodoSelect) {
        periodoSelect.addEventListener('change', function() {
            const periodoIdSeleccionado = this.value;
            console.log(`Periodo cambiado a: ${periodoIdSeleccionado}.`);
        });
    }

    // Carga inicial de datos
    cargarDatosDelHeader();
});