<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Tienda | AdminLTE 4</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AdminLTE 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css"
        crossorigin="anonymous">
    <!-- SweetAlert2 para alertas modernas -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .required-label::after {
            content: " *";
            color: red;
            font-weight: bold;
        }

        .small-box {
            border-radius: 0.25rem;
            position: relative;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        }

        .inner {
            padding: 10px;
        }

        .inner h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px;
            white-space: nowrap;
            padding: 0;
            color: #fff;
        }

        .inner p {
            font-size: 1rem;
            color: #fff;
        }

        .icon {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 0;
            font-size: 70px;
            color: rgba(0, 0, 0, .15);
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- BARRA SUPERIOR -->
        <nav class="app-header navbar navbar-expand bg-body shadow-sm">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i
                                class="fa-solid fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-block">
                        <h5 class="navbar-text fw-bold mb-0">Udenar - Dashboard de Inventario</h5>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- MENU LATERAL -->
        <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
            <div class="sidebar-brand text-center py-4">
                <h3 class="text-white fw-bold mb-0"><i class="fa-solid fa-store me-2"></i>MiTienda</h3>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-3">
                    <ul class="nav sidebar-menu flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" onclick="cambiarSeccion('productos', this)">
                                <i class="nav-icon fa-solid fa-box"></i>
                                <p>Productos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="cambiarSeccion('categorias', this)">
                                <i class="nav-icon fa-solid fa-tags"></i>
                                <p>Categorías</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="app-main p-4">
            <!-- WIDGETS COLORIDOS (Como en la foto) -->
            <div class="container-fluid">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="small-box bg-primary p-3 rounded shadow-sm text-white">
                            <div class="inner">
                                <h3 id="stat-productos">0</h3>
                                <p>Productos Totales</p>
                            </div>
                            <div class="icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="small-box bg-success p-3 rounded shadow-sm text-white">
                            <div class="inner">
                                <h3 id="stat-stock">0</h3>
                                <p>Stock Global</p>
                            </div>
                            <div class="icon"><i class="fa-solid fa-warehouse"></i></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="small-box bg-warning p-3 rounded shadow-sm text-dark">
                            <div class="inner">
                                <h3 id="stat-categorias">0</h3>
                                <p>Categorías</p>
                            </div>
                            <div class="icon"><i class="fa-solid fa-tags"></i></div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN PRODUCTOS -->
                <div id="seccion-productos">
                    <div class="card shadow-sm border-0 mb-4">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0 text-primary"><i class="fa-solid fa-list me-2"></i>Lista
                                de Productos</h5>
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
                                            <td colspan="6" class="text-center">Cargando datos...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN CATEGORÍAS -->
                <div id="seccion-categorias" style="display: none;">
                    <div class="card shadow-sm border-0">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0 text-warning"><i
                                    class="fa-solid fa-tags me-2"></i>Gestión de Categorías</h5>
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
                                            <th>Nombre de Categoría</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaCats">
                                        <tr>
                                            <td colspan="3" class="text-center">No hay categorías que mostrar</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="app-footer bg-white border-top text-center py-3">
            <small class="text-muted">Desarrollado para Tarea CRUD - AdminLTE 4 © 2024</small>
        </footer>
    </div>

    <!-- MODAL AGREGAR PRODUCTO -->
    <div class="modal fade" id="modal-agregar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-circle-plus me-2"></i>Nuevo Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-agregar">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4"><label class="required-label">ID Producto</label><input type="number"
                                    id="id_prod" class="form-control" required></div>
                            <div class="col-md-8"><label class="required-label">Nombre</label><input type="text"
                                    id="nombre" class="form-control" required></div>
                            <div class="col-md-4"><label class="required-label">Precio</label><input type="number"
                                    id="precio" class="form-control" step="0.01" required></div>
                            <div class="col-md-4"><label class="required-label">Stock inicial</label><input
                                    type="number" id="stock" class="form-control" required></div>
                            <div class="col-md-4">
                                <label>Categoría</label>
                                <div class="input-group">
                                    <select id="cat_p" class="form-select"></select>
                                    <button type="button" class="btn btn-outline-success"
                                        onclick="abrirModalCatRapido('modal-agregar')">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light"><button type="submit" class="btn btn-primary px-4">Guardar
                            Producto</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR PRODUCTO -->
    <div class="modal fade" id="modal-editar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-edit me-2"></i>Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-editar">
                    <div class="modal-body p-4">
                        <input type="hidden" id="edit-id">
                        <div class="row g-3">
                            <div class="col-md-12"><label class="required-label">Nombre</label><input type="text"
                                    id="edit-nombre" class="form-control" required></div>
                            <div class="col-md-4"><label class="required-label">Precio</label><input type="number"
                                    id="edit-precio" class="form-control" step="0.01" required></div>
                            <div class="col-md-4"><label class="required-label">Stock</label><input type="number"
                                    id="edit-stock" class="form-control" required></div>
                            <div class="col-md-4">
                                <label>Categoría</label>
                                <div class="input-group">
                                    <select id="edit-cat" class="form-select"></select>
                                    <button type="button" class="btn btn-outline-warning"
                                        onclick="abrirModalCatRapido('modal-editar')">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light"><button type="submit" class="btn btn-success px-4">Actualizar
                            Datos</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL AGREGAR CATEGORIA -->
    <div class="modal fade" id="modal-cat-agregar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">Nueva Categoría</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <form id="form-cat-agregar">
                    <div class="modal-body p-4"><label class="mb-1">ID</label><input type="number" id="id_cat"
                            class="form-control mb-3" required><label class="mb-1">Nombre</label><input type="text"
                            id="nombre_cat" class="form-control" required></div>
                    <div class="modal-footer bg-light"><button type="submit" class="btn btn-warning">Crear
                            Categoría</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR CATEGORIA -->
    <div class="modal fade" id="modal-cat-editar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">Editar Categoría</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <form id="form-cat-editar">
                    <div class="modal-body p-4"><input type="hidden" id="edit-cat-id"><label
                            class="mb-1">Nombre</label><input type="text" id="edit-cat-nombre" class="form-control"
                            required></div>
                    <div class="modal-footer bg-light"><button type="submit" class="btn btn-warning">Actualizar
                            Categoría</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/crud.js"></script>
</body>

</html>