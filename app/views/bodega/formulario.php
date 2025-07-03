


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Bodega</title>
    <link rel="stylesheet" href="css/bodega.css">
    <link rel="stylesheet" href="css/formulario-bodega.css">
    <link rel="stylesheet" href="css/tabla-bodega.css">
</head>

<body class="container">
    <b class=>Ingresar Nueva Bodega</b>
    <br></br>

    <form id="form-bodega" class="form" action="/bodega" method="POST">

        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" maxlength="100" required>
        </div>

        <div class="form-group">
            <label>Dirección:</label>
            <input type="text" name="direccion">
        </div>

        <div class="form-group">
            <label>Dotación:</label>
            <input type="number" name="dotacion" min="0" value="0">
        </div>

        <div class="form-group">
            <label>Encargado:</label>
            <select name="encargado_id">
                <?php foreach ($encargados as $encargado): ?>
                    <option value="<?= $encargado['id'] ?>">
                        <?= $encargado['nombre'] . ' ' . $encargado['apellido_paterno'] . ' ' . $encargado['apellido_materno'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <button type="submit">Crear Bodega</button>
        </div>

    </form>
    <div id="mensajeCreacion" style="margin-top: 1rem;"></div>
 <!-- aqui agregamos el javascript que posee la logica -->
    <script src="/js/bodega-form.js"></script>
</body>

</html>