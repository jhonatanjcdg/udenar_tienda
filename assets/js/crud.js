// Listas locales para búsquedas rápidas
let listaProductos = [];
let listaCategorias = [];
let ultimaCategoriaId = null;
let modalPadreId = null; // Guardar qué modal nos llamó (Agregar o Editar)

// El dispatcher que usas como switch
async function ejecutar(accion, datos = null) {
    try {
        switch (accion) {
            case 'obtener': return await fetch_obtener();
            case 'crear': return await fetch_crear(datos);
            case 'actualizar': return await fetch_actualizar(datos);
            case 'eliminar': return await fetch_eliminar(datos.id);
            case 'obtener_cats': return await fetch_obtener_cats();
            case 'crear_cat': return await fetch_crear_cat(datos);
            case 'actualizar_cat': return await fetch_actualizar_cat(datos);
            case 'eliminar_cat': return await fetch_eliminar_cat(datos.id);
            case 'stats': return await fetch_stats();
        }
    } catch (e) {
        console.error("Error en ejecutar:", e);
        Swal.fire('Error', 'Ocurrió un error inesperado', 'error');
    }
}

// --- STATS ---
async function fetch_stats() {
    let r = await fetch('api/api.php?action=obtener_stats');
    let d = await r.json();
    document.getElementById('stat-productos').innerText = d.total_productos;
    document.getElementById('stat-categorias').innerText = d.total_categorias;
    document.getElementById('stat-stock').innerText = d.total_stock;
}

// --- PRODUCTOS ---
async function fetch_obtener() {
    let r = await fetch('api/api.php?action=obtener');
    let d = await r.json();
    listaProductos = Array.isArray(d) ? d : [];
    return listaProductos;
}

async function fetch_crear(pro) {
    let r = await fetch('api/api.php', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'crear', ...pro })
    });
    let res = await r.json();
    if (res.exito) {
        Swal.fire({
            title: '¡Guardado!',
            text: 'El producto se registró correctamente',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        await refrescarTodo();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-agregar')).hide();
    } else {
        Swal.fire('Error', res.error || 'No se pudo guardar', 'error');
    }
}

async function fetch_actualizar(pro) {
    let r = await fetch('api/api.php', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'actualizar', ...pro })
    });
    let res = await r.json();
    if (res.exito) {
        Swal.fire({
            title: '¡Actualizado!',
            text: 'Los datos se modificaron con éxito',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        await refrescarTodo();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-editar')).hide();
    } else {
        Swal.fire('Error', 'No se pudo actualizar', 'error');
    }
}

async function fetch_eliminar(id) {
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar'
    });

    if (result.isConfirmed) {
        await fetch('api/api.php', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'eliminar', id: id })
        });
        await refrescarTodo();
        Swal.fire('Borrado', 'El producto ha sido eliminado.', 'success');
    }
}

// --- CATEGORIAS ---
async function fetch_obtener_cats() {
    let r = await fetch('api/api.php?action=obtener_cats');
    let d = await r.json();
    listaCategorias = Array.isArray(d) ? d : [];
    return listaCategorias;
}

async function fetch_crear_cat(cat) {
    let r = await fetch('api/api.php', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'crear_cat', ...cat })
    });
    let res = await r.json();
    if (res.exito) {
        ultimaCategoriaId = cat.id; // Guardamos el ID para seleccionarlo luego
        Swal.fire({
            title: 'Categoría Lista',
            text: 'Ya puedes usarla en tus productos',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        await refrescarTodo();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-cat-agregar')).hide();
    } else {
        Swal.fire('Ups!', 'Ese ID ya existe o el nombre es inválido', 'error');
    }
}

async function fetch_actualizar_cat(cat) {
    let r = await fetch('api/api.php', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'actualizar_cat', ...cat })
    });
    let res = await r.json();
    if (res.exito) {
        Swal.fire('Éxito', 'Categoría actualizada correctamente', 'success');
        await refrescarTodo();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-cat-editar')).hide();
    }
}

async function fetch_eliminar_cat(id) {
    const result = await Swal.fire({
        title: '¿Eliminar categoría?',
        text: "Asegúrate de que no tenga productos vinculados",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Borrar',
        cancelButtonText: 'Mejor no'
    });

    if (result.isConfirmed) {
        let r = await fetch('api/api.php', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'eliminar_cat', id: id })
        });
        let res = await r.json();
        if (!res.exito) {
            Swal.fire('Error', 'No se pudo borrar. Probablemente tiene productos asignados.', 'error');
        } else {
            Swal.fire('Hecho', 'Categoría eliminada', 'success');
        }
        await refrescarTodo();
    }
}

// --- UI ---
// Función para no perder el progreso del producto al crear una categoría
function abrirModalCatRapido(idPadre) {
    modalPadreId = idPadre;
    const modalP = bootstrap.Modal.getOrCreateInstance(document.getElementById(idPadre));
    modalP.hide(); // Ocultamos el de producto momentáneamente

    const modalC = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-cat-agregar'));
    modalC.show();
}

async function refrescarTodo() {
    try {
        await ejecutar('stats');
        let cats = await ejecutar('obtener_cats');
        llenarSelects(cats || []);
        dibujarTablaCategorias(cats || []);
        let prods = await ejecutar('obtener');
        dibujarTablaProductos(prods || []);
    } catch (err) {
        console.error("Error al refrescar:", err);
    }
}

function llenarSelects(cats) {
    const s1 = document.getElementById('cat_p');
    const s2 = document.getElementById('edit-cat');
    [s1, s2].forEach(s => {
        if (!s) return;
        const valorActual = s.value; // Guardamos lo que el usuario ya habia elegido
        s.innerHTML = '<option value="">(Sin categoría)</option>';
        cats.forEach(c => {
            s.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
        });

        // Si acabamos de crear una, la seleccionamos. Si no, dejamos lo que estaba.
        if (ultimaCategoriaId) {
            s.value = ultimaCategoriaId;
        } else if (valorActual) {
            s.value = valorActual;
        }
    });
    ultimaCategoriaId = null; // Limpiamos para la proxima
}

function dibujarTablaProductos(datos) {
    let tb = document.getElementById('cuerpoTabla');
    if (!tb) return; tb.innerHTML = '';
    datos.forEach(p => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${p.id}</td>
            <td><b>${p.name}</b></td>
            <td class="text-success fw-bold">$${Number(p.price).toFixed(2)}</td>
            <td><span class="badge ${p.stock < 5 ? 'bg-danger' : 'bg-success'}">${p.stock}</span></td>
            <td><span class="badge bg-secondary">${p.cat_nombre || 'General'}</span></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick='abrirModalEditar(${JSON.stringify(p)})'><i class="fa-solid fa-edit"></i></button>
                <button class="btn btn-danger btn-sm" onclick="ejecutar('eliminar', {id: ${p.id}})"><i class="fa-solid fa-trash"></i></button>
            </td>`;
        tb.appendChild(tr);
    });
}

function dibujarTablaCategorias(datos) {
    let tb = document.getElementById('cuerpoTablaCats');
    if (!tb) return; tb.innerHTML = '';
    datos.forEach(c => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${c.id}</td>
            <td>${c.nombre}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick='abrirModalEditarCat(${JSON.stringify(c)})'><i class="fa-solid fa-edit"></i></button>
                <button class="btn btn-danger btn-sm" onclick="ejecutar('eliminar_cat', {id: ${c.id}})"><i class="fa-solid fa-trash"></i></button>
            </td>`;
        tb.appendChild(tr);
    });
}

function abrirModalEditar(p) {
    document.getElementById('edit-id').value = p.id;
    document.getElementById('edit-nombre').value = p.name;
    document.getElementById('edit-precio').value = p.price;
    document.getElementById('edit-stock').value = p.stock;
    document.getElementById('edit-cat').value = p.categoria_id || "";
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-editar')).show();
}

function abrirModalEditarCat(c) {
    document.getElementById('edit-cat-id').value = c.id;
    document.getElementById('edit-cat-nombre').value = c.nombre;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-cat-editar')).show();
}

function cambiarSeccion(nombre, link) {
    document.getElementById('seccion-productos').style.display = nombre === 'productos' ? 'block' : 'none';
    document.getElementById('seccion-categorias').style.display = nombre === 'categorias' ? 'block' : 'none';
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    link.classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
    refrescarTodo();

    document.getElementById('form-agregar')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        await ejecutar('crear', {
            id: document.getElementById('id_prod').value, name: document.getElementById('nombre').value,
            price: document.getElementById('precio').value, stock: document.getElementById('stock').value,
            categoria_id: document.getElementById('cat_p').value
        });
        e.target.reset();
    });

    document.getElementById('form-editar')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        await ejecutar('actualizar', {
            id: document.getElementById('edit-id').value, name: document.getElementById('edit-nombre').value,
            price: document.getElementById('edit-precio').value, stock: document.getElementById('edit-stock').value,
            categoria_id: document.getElementById('edit-cat').value
        });
    });

    document.getElementById('form-cat-agregar')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        await ejecutar('crear_cat', { id: document.getElementById('id_cat').value, nombre: document.getElementById('nombre_cat').value });
        e.target.reset();
    });

    document.getElementById('form-cat-editar')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        await ejecutar('actualizar_cat', { id: document.getElementById('edit-cat-id').value, nombre: document.getElementById('edit-cat-nombre').value });
    });

    document.getElementById('inputBuscar')?.addEventListener('keyup', (e) => {
        let b = e.target.value.toLowerCase();
        dibujarTablaProductos(listaProductos.filter(p => p.name.toLowerCase().includes(b) || p.id.toString().includes(b)));
    });

    // Listener para cuando se cierra el modal de categorias:
    // Si venimos desde un producto (Agregar/Editar), que regrese a ese formulario
    document.getElementById('modal-cat-agregar')?.addEventListener('hidden.bs.modal', () => {
        if (modalPadreId) {
            const modalPadre = bootstrap.Modal.getOrCreateInstance(document.getElementById(modalPadreId));
            modalPadre.show();
            modalPadreId = null; // Reset
        }
    });
});
