// La función ahora está fuera para ser accesible globalmente
function cargarDatosDelHeader() {
    fetch('../../controller/AppController.php?action=cargarDatosHeader')
        .then(response => response.json())
        .then(data => {
            const sedeSelect = document.getElementById('sede-select');
            const periodoSelect = document.getElementById('periodo-select');

            if (data.success && sedeSelect && periodoSelect) {
                // Poblar coordinaciones
                sedeSelect.innerHTML = '';
                if (data.data.coordinaciones && data.data.coordinaciones.length > 0) {
                    data.data.coordinaciones.forEach(coord => {
                        const option = document.createElement('option');
                        option.value = coord.id_coordinacion;
                        option.textContent = `${coord.alias_coordinacion} - ${coord.nombre_coordinacion}`;
                        sedeSelect.appendChild(option);
                    });
                } else {
                    sedeSelect.innerHTML = '<option value="">No hay coordinaciones</option>';
                }

                // Poblar periodos
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

// Hacemos la función accesible globalmente a través del objeto window
window.refrescarHeader = cargarDatosDelHeader;

document.addEventListener('DOMContentLoaded', function() {
    // La llamamos una vez al cargar la página
    window.refrescarHeader();

    // Listeners que ya tenías
    const sedeSelect = document.getElementById('sede-select');
    const periodoSelect = document.getElementById('periodo-select');
    if (sedeSelect) {
        sedeSelect.addEventListener('change', function() {
            console.log(`Coordinación cambiada a: ${this.value}.`);
        });
    }
    if (periodoSelect) {
        periodoSelect.addEventListener('change', function() {
            console.log(`Periodo cambiado a: ${this.value}.`);
        });
    }
});