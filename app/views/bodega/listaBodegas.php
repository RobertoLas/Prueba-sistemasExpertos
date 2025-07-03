<body>
    <br></br>

    <div style="margin-bottom: 1rem;">
        <label for="filtroEstado">Filtrar por estado:</label>
        <select id="filtroEstado" onchange="filtrarEstado(this.value)">
            <option value="Todos">Todos</option>
            <option value="Activada">Activada</option>
            <option value="Desactivada">Desactivada</option>
        </select>
    </div>

    <table class="bodega-table" id="tablaBodegas">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Dotación</th>
                <th>Estado</th>
                <th>Encargado</th>
                <th>Creado en</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bodegas as $bodega): ?>
                <tr data-estado="<?= $bodega['estado'] ?>">
                    <td><?= $bodega['codigo'] ?></td>

                    <td data-editable="nombre"><?= htmlspecialchars($bodega['nombre']) ?></td>
                    <td data-editable="direccion"><?= htmlspecialchars($bodega['direccion']) ?></td>
                    <td data-editable="dotacion"><?= htmlspecialchars($bodega['dotacion']) ?></td>
                    <td><?= $bodega['estado'] ?></td>

                    <td data-editable-select data-encargado-id="<?= $bodega['encargado_id'] ?>">
                        <?= htmlspecialchars($bodega['encargado_nombre'] . ' ' . $bodega['encargado_paterno'] . ' ' . $bodega['encargado_materno']) ?>
                    </td>

                    <td><?= $bodega['creado_en'] ?></td>


                    <td>
                        <div class="bodega-table-actions">
                            <button class="bodega-btn edit" onclick="habilitarEdicion(this)">Editar</button>

                            <button class="bodega-btn delete"
                                onclick="confirmarDesactivar('<?= $bodega['codigo'] ?>')">Eliminar</button>
                            <button class="bodega-btn save" style="display: none;"
                                onclick="guardarCambios(this)">Guardar</button>
                            <button class="bodega-btn cancel" style="display: none;"
                                onclick="deshacerEdicion(this)">Deshacer</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        window.listaEncargados = <?= json_encode($encargados) ?>;
    </script>
    <!-- aqui agregamos el javascript que posee la logica -->
    <script src="/js/bodega-tabla.js"></script>
</body>