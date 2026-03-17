<!DOCTYPE html>
<html lang="es">

<?php include 'components/head.php'; ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <?php include 'components/navbar.php'; ?>

        <?php include 'components/sidebar.php'; ?>
        <main class="app-main p-4">

            <div class="container-fluid">

                <?php include 'components/widgets.php'; ?>

                <div id="seccion-productos">
                    <div class="card shadow-sm border-0 mb-4">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0 text-primary">
                                <i class="fa-solid fa-list me-2"></i>Lista de Productos
                            </h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-agregar">
                                <i class="fa-solid fa-plus me-1"></i> Añadir Producto
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i
                                                class="fa-solid fa-magnifying-glass"></i></span>
                                        <input type="text" id="inputBuscar" class="form-control"
                                            placeholder="Buscar por nombre o ID...">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                            <th>Categoría</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTabla">
                                        <tr>
                                            <td colspan="6" class="text-center italic text-muted">Cargando almacén...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="seccion-categorias" style="display: none;">
                    <div class="card shadow-sm border-0">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0 text-warning">
                                <i class="fa-solid fa-tags me-2"></i>Gestión de Categorías
                            </h5>
                            <button class="btn btn-warning text-dark" data-bs-toggle="modal"
                                data-bs-target="#modal-cat-agregar">
                                <i class="fa-solid fa-plus me-1"></i> Nueva Categoría
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaCats">
                                        <!-- Se llena con JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <?php include 'components/footer.php'; ?>
    </div>

    <?php include 'components/modals.php'; ?>

</body>

</html>