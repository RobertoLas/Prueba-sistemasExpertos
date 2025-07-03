function habilitarEdicion(btnEditar) {
  const fila = btnEditar.closest('tr');
  const eliminarBtn = fila.querySelector('.bodega-btn.delete');
  const guardarBtn = fila.querySelector('.bodega-btn.save');
  const deshacerBtn = fila.querySelector('.bodega-btn.cancel');

  btnEditar.style.display = "none";
  eliminarBtn.style.display = "none";
  guardarBtn.style.display = "inline-block";
  deshacerBtn.style.display = "inline-block";

  const editables = fila.querySelectorAll('[data-editable]');
  const selectEncargado = fila.querySelector('[data-editable-select]');

  editables.forEach(td => {
    td.dataset.original = td.innerText.trim();
    const campo = td.getAttribute('data-editable');
    const valor = td.innerText.trim();
    const input = document.createElement('input');

    // aqui separe la dotacion para que quede como numerica
    if (campo === 'dotacion') {
      input.type = 'number';
      input.min = 0;
      input.step = 1;
      input.addEventListener('input', () => {
        if (input.value < 0) input.value = 0;
        input.value = input.value.replace(/[^\d]/g, '');
      });
    } else {
      input.type = 'text';
    }
    input.value = valor;
    input.classList.add('bodega-input-edit');
    input.style.width = td.offsetWidth + "px";
    td.innerHTML = '';
    td.appendChild(input);
  });

  if (selectEncargado) {
    const encargadoId = selectEncargado.dataset.encargadoId;
    selectEncargado.dataset.original = selectEncargado.innerText.trim();
    selectEncargado.innerHTML = '';
    const select = document.createElement('select');
    select.classList.add('bodega-input-edit');

    const encargados = window.listaEncargados || [];

    encargados.forEach(enc => {
      const option = document.createElement('option');
      option.value = enc.id;
      option.text = `${enc.nombre} ${enc.apellido_paterno} ${enc.apellido_materno}`;
      if (enc.id == encargadoId) option.selected = true;
      select.appendChild(option);
    });

    selectEncargado.appendChild(select);
  }
}

function deshacerEdicion(btnCancelar) {
  const fila = btnCancelar.closest('tr');
  const editarBtn = fila.querySelector('.bodega-btn.edit');
  const eliminarBtn = fila.querySelector('.bodega-btn.delete');
  const guardarBtn = fila.querySelector('.bodega-btn.save');
  const cancelBtn = btnCancelar;

  editarBtn.style.display = "inline-block";
  eliminarBtn.style.display = "inline-block";
  guardarBtn.style.display = "none";
  cancelBtn.style.display = "none";

  const editables = fila.querySelectorAll('[data-editable]');
  const selectEncargado = fila.querySelector('[data-editable-select]');

  editables.forEach(td => {
    td.innerText = td.dataset.original;
  });

  if (selectEncargado) {
    selectEncargado.innerText = selectEncargado.dataset.original;
  }
}

function guardarCambios(btnGuardar) {
  const fila = btnGuardar.closest('tr');
  const editarBtn = fila.querySelector('.bodega-btn.edit');
  const eliminarBtn = fila.querySelector('.bodega-btn.delete');
  const cancelBtn = fila.querySelector('.bodega-btn.cancel');
  const inputs = fila.querySelectorAll('[data-editable] input');
  const select = fila.querySelector('[data-editable-select] select');
  const datos = {
    codigo: fila.children[0].innerText.trim(),
    nombre: inputs[0].value.trim(),
    direccion: inputs[1].value.trim(),
    dotacion: inputs[2].value.trim(),
    encargado_id: select.value,
    accion: 'editar'
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
      if (data.success) {
        alert("Bodega actualizada correctamente.");
        actualizarTabla();
      } else {
        alert("Error al actualizar la bodega.");
        return
      }
    });


  inputs[0].parentElement.innerText = datos.nombre;
  inputs[1].parentElement.innerText = datos.direccion;
  inputs[2].parentElement.innerText = datos.dotacion;
  select.parentElement.innerText = select.options[select.selectedIndex].text;
  editarBtn.style.display = "inline-block";
  eliminarBtn.style.display = "inline-block";
  btnGuardar.style.display = "none";
  cancelBtn.style.display = "none";
}

// controla el filtro por estado 
function filtrarEstado(estadoSeleccionado) {
  const filas = document.querySelectorAll('#tablaBodegas tbody tr');

  filas.forEach(fila => {
    const estadoFila = fila.getAttribute('data-estado');

    if (estadoSeleccionado === 'Todos' || estadoFila === estadoSeleccionado) {
      fila.style.display = '';
    } else {
      fila.style.display = 'none';
    }
  });
}


//aqui creamos la tabla completa nuevamente para evitar actualizar  el front
function actualizarTabla() {
  fetch('/bodega/listar')
    .then(res => res.json())
    .then(bodegas => {
      const tbody = document.querySelector('#tablaBodegas tbody');
      tbody.innerHTML = '';
      bodegas.forEach(bodega => {
        const tr = document.createElement('tr');
        tr.setAttribute('data-estado', bodega.estado);
        tr.innerHTML = `
          <td>${bodega.codigo}</td>
          <td data-editable="nombre">${bodega.nombre}</td>
          <td data-editable="direccion">${bodega.direccion}</td>
          <td data-editable="dotacion">${bodega.dotacion}</td>
          <td>${bodega.estado}</td>
          
          <td data-editable-select data-encargado-id="${bodega.encargado_id}">
            ${bodega.encargado_nombre} ${bodega.encargado_paterno} ${bodega.encargado_materno}
          </td>
          <td>${bodega.creado_en}</td>
          <td>
            <div class="bodega-table-actions">
              <button class="bodega-btn edit" onclick="habilitarEdicion(this)">Editar</button>
              <button class="bodega-btn delete" onclick="confirmarDesactivar('${bodega.codigo}')">Eliminar</button>
              <button class="bodega-btn save" style="display: none;" onclick="guardarCambios(this)">Guardar</button>
              <button class="bodega-btn cancel" style="display: none;" onclick="deshacerEdicion(this)">Deshacer</button>
            </div>
          </td>
        `;

        tbody.appendChild(tr);
      });
    })
    .catch(err => console.error('Error actualizando tabla:', err));
}


function confirmarDesactivar(codigo) {
  const confirmar = confirm("¿Seguro que deseas desactivar esta bodega? Esta acción no se puede deshacer.");
  if (confirmar) {
    fetch('/bodega', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ codigo, accion: 'desactivar' }),
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Bodega desactivada correctamente.');
          actualizarTabla();
        } else {
          alert('Error al desactivar la bodega: ' + data.message);
        }
      })
      .catch(err => {
        alert('Error de conexión');
        console.error(err);
      });
  }
}