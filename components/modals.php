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
                        <div class="col-md-8"><label class="required-label">Nombre</label><input type="text" id="nombre"
                                class="form-control" required></div>
                        <div class="col-md-4"><label class="required-label">Precio</label><input type="number"
                                id="precio" class="form-control" step="0.01" required></div>
                        <div class="col-md-4"><label class="required-label">Stock inicial</label><input type="number"
                                id="stock" class="form-control" required></div>
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
                <div class="modal-body p-4">
                    <label class="mb-1">ID</label><input type="number" id="id_cat" class="form-control mb-3" required>
                    <label class="mb-1">Nombre</label><input type="text" id="nombre_cat" class="form-control" required>
                </div>
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
                <div class="modal-body p-4">
                    <input type="hidden" id="edit-cat-id">
                    <label class="mb-1">Nombre</label><input type="text" id="edit-cat-nombre" class="form-control"
                        required>
                </div>
                <div class="modal-footer bg-light"><button type="submit" class="btn btn-warning">Actualizar
                        Categoría</button></div>
            </form>
        </div>
    </div>
</div>