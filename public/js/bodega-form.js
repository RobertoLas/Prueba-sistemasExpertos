document.getElementById('form-bodega').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;
    const datos = {
        nombre: form.nombre.value.trim(),
        direccion: form.direccion.value.trim(),
        dotacion: parseInt(form.dotacion.value.trim(), 10),
        encargado_id: parseInt(form.encargado_id.value, 10),
        accion: 'crear'
    };
    fetch('/bodega', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
        .then(res => res.json())
        .then(data => {
            const mensajeDiv = document.getElementById('mensajeCreacion');
            if (data.success) {
                mensajeDiv.textContent = '✅ Bodega creada correctamente.';
                mensajeDiv.style.color = 'green';
                form.reset();
                actualizarTabla();
            } else {
                mensajeDiv.textContent = '❌ Error: ' + (data.message || 'No se pudo crear');
                mensajeDiv.style.color = 'red';
            }
        })
        .catch(err => {
            console.error('Error al crear bodega:', err);
        });
});