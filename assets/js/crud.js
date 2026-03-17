/**
 * ARCHIVO: crud.js
 * Descripción: Maneja toda la lógica del frontend: comunicación con el servidor (API) 
 * y actualización de la interfaz de usuario (DOM).
 */

// --- 1. ESTADO DE LA APLICACIÓN ---
let listaProductos = [];
let listaCategorias = [];
let ultimaCategoriaId = null;
let modalPadreId = null;

// --- 2. SERVICIOS API (Fetch) ---
// Centralizamos las llamadas al servidor aquí.
const apiService = {
    async query(action, params = {}) {
        const isGet = action === 'obtener' || action === 'obtener_cats' || action === 'obtener_stats';
        const url = `api/api.php${isGet ? '?action=' + action : ''}`;

        const options = {
            method: isGet ? 'GET' : 'POST',
            headers: { 'Content-Type': 'application/json' }
        };

        if (!isGet) {
            options.body = JSON.stringify({ action, ...params });
        }

        const response = await fetch(url, options);
        return await response.json();
    }
};

// --- 3. LÓGICA DE NEGOCIO (Controladores) ---
// Funciones que deciden qué hacer con los datos.
async function gestionarAccion(accion, datos = null) {
    try {
        switch (accion) {
            case 'stats':
                const stats = await apiService.query('obtener_stats');
                document.getElementById('stat-productos').innerText = stats.total_productos;
                document.getElementById('stat-categorias').innerText = stats.total_categorias;
                document.getElementById('stat-stock').innerText = stats.total_stock;
                break;

            case 'obtener_productos':
                listaProductos = await apiService.query('obtener');
                uiManager.dibujarTablaProductos(listaProductos);
                break;

            case 'crear_producto':
                const resCrearP = await apiService.query('crear', datos);
                if (resCrearP.exito) {
                    uiManager.notificar('¡Guardado!', 'success');
                    await refrescarTodo();
                    uiManager.cerrarModal('modal-agregar');
                }
                break;

            case 'actualizar_producto':
                const resEditP = await apiService.query('actualizar', datos);
                if (resEditP.exito) {
                    uiManager.notificar('¡Actualizado!', 'success');
                    await refrescarTodo();
                    uiManager.cerrarModal('modal-editar');
                }
                break;

            case 'eliminar_producto':
                const confirmP = await uiManager.confirmar('¿Borrar producto?', 'Esta acción no se puede deshacer');
                if (confirmP.isConfirmed) {
                    await apiService.query('eliminar', { id: datos.id });
                    await refrescarTodo();
                    uiManager.notificar('Eliminado', 'success');
                }
                break;

            case 'obtener_categorias':
                listaCategorias = await apiService.query('obtener_cats');
                uiManager.dibujarTablaCategorias(listaCategorias);
                uiManager.llenarSelects(listaCategorias);
                break;

            case 'crear_categoria':
                const resCrearC = await apiService.query('crear_cat', datos);
                if (resCrearC.exito) {
                    ultimaCategoriaId = datos.id;
                    uiManager.notificar('Categoría Lista', 'success');
                    await refrescarTodo();
                    uiManager.cerrarModal('modal-cat-agregar');
                }
                break;

            case 'actualizar_categoria':
                const resEditC = await apiService.query('actualizar_cat', datos);
                if (resEditC.exito) {
                    uiManager.notificar('¡Actualizado!', 'success');
                    await refrescarTodo();
                    uiManager.cerrarModal('modal-cat-editar');
                }
                break;

            case 'eliminar_categoria':
                const confirmC = await uiManager.confirmar('¿Borrar categoría?', 'Asegúrate de que no tenga productos');
                if (confirmC.isConfirmed) {
                    const resDelC = await apiService.query('eliminar_cat', { id: datos.id });
                    if (resDelC.exito) {
                        await refrescarTodo();
                        uiManager.notificar('Eliminado', 'success');
                    } else {
                        uiManager.notificar('Error', 'error', 'Tiene productos vinculados');
                    }
                }
                break;
        }
    } catch (e) {
        console.error("Error en ejecutar:", e);
        uiManager.notificar('Ups!', 'error', 'Ocurrió un error inesperado');
    }
}

// --- 4. GESTOR DE INTERFAZ (UI Manager) ---
// Funciones que tocan el HTML directamente.
const uiManager = {
    dibujarTablaProductos(datos) {
        let tb = document.getElementById('cuerpoTabla');
        if (!tb) return;
        
        // Evitamos errores si datos no es un array (por errores de API)
        if (!Array.isArray(datos)) {
            console.error("dibujarTablaProductos: 'datos' no es un array", datos);
            tb.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar datos del almacén</td></tr>';
            return;
        }

        tb.innerHTML = datos.map(p => `
            <tr>
                <td>${p.id}</td>
                <td><b>${p.name}</b></td>
                <td class="text-success fw-bold">$${Number(p.price).toFixed(2)}</td>
                <td><span class="badge ${p.stock < 5 ? 'bg-danger' : 'bg-success'}">${p.stock}</span></td>
                <td><span class="badge bg-secondary">${p.cat_nombre || 'General'}</span></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick='prepararEdicionProducto(${JSON.stringify(p)})'><i class="fa-solid fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="gestionarAccion('eliminar_producto', {id: ${p.id}})"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="6" class="text-center italic">No hay productos</td></tr>';
    },

    dibujarTablaCategorias(datos) {
        let tb = document.getElementById('cuerpoTablaCats');
        if (!tb) return;

        if (!Array.isArray(datos)) {
            console.error("dibujarTablaCategorias: 'datos' no es un array", datos);
            tb.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar categorías</td></tr>';
            return;
        }

        tb.innerHTML = datos.map(c => `
            <tr>
                <td>${c.id}</td>
                <td>${c.nombre}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick='prepararEdicionCat(${JSON.stringify(c)})'><i class="fa-solid fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="gestionarAccion('eliminar_categoria', {id: ${c.id}})"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="3" class="text-center">No hay categorías</td></tr>';
    },

    llenarSelects(cats) {
        const selects = ['cat_p', 'edit-cat'].map(id => document.getElementById(id)).filter(s => s);
        selects.forEach(s => {
            const valorPrevio = s.value;
            s.innerHTML = '<option value="">(Sin categoría)</option>' +
                cats.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');

            if (ultimaCategoriaId) s.value = ultimaCategoriaId;
            else if (valorPrevio) s.value = valorPrevio;
        });
        ultimaCategoriaId = null;
    },

    notificar(titulo, icono, texto = '') {
        Swal.fire({ title: titulo, text: texto, icon: icono, timer: 2000, showConfirmButton: false });
    },

    async confirmar(titulo, texto) {
        return await Swal.fire({
            title: titulo, text: texto, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, proceder', cancelButtonText: 'No, cancelar'
        });
    },

    cerrarModal(id) {
        bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).hide();
    },

    abrirModal(id) {
        bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).show();
    }
};

// --- 5. FUNCIONES DE AYUDA (Helpers) ---
async function refrescarTodo() {
    await gestionarAccion('stats');
    await gestionarAccion('obtener_categorias');
    await gestionarAccion('obtener_productos');
}

function prepararEdicionProducto(p) {
    document.getElementById('edit-id').value = p.id;
    document.getElementById('edit-nombre').value = p.name;
    document.getElementById('edit-precio').value = p.price;
    document.getElementById('edit-stock').value = p.stock;
    document.getElementById('edit-cat').value = p.categoria_id || "";
    uiManager.abrirModal('modal-editar');
}

function prepararEdicionCat(c) {
    document.getElementById('edit-cat-id').value = c.id;
    document.getElementById('edit-cat-nombre').value = c.nombre;
    uiManager.abrirModal('modal-cat-editar');
}

function abrirModalCatRapido(idPadre) {
    modalPadreId = idPadre;
    uiManager.cerrarModal(idPadre);
    uiManager.abrirModal('modal-cat-agregar');
}

function cambiarSeccion(nombre, link) {
    ['productos', 'categorias'].forEach(sec => {
        document.getElementById(`seccion-${sec}`).style.display = sec === nombre ? 'block' : 'none';
    });
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    link.classList.add('active');
}

// --- 6. EVENTOS PRINCIPALES ---
document.addEventListener('DOMContentLoaded', () => {
    refrescarTodo();

    // Formularios de Productos
    document.getElementById('form-agregar')?.addEventListener('submit', (e) => {
        e.preventDefault();
        gestionarAccion('crear_producto', {
            id: document.getElementById('id_prod').value,
            name: document.getElementById('nombre').value,
            price: document.getElementById('precio').value,
            stock: document.getElementById('stock').value,
            categoria_id: document.getElementById('cat_p').value
        });
        e.target.reset();
    });

    document.getElementById('form-editar')?.addEventListener('submit', (e) => {
        e.preventDefault();
        gestionarAccion('actualizar_producto', {
            id: document.getElementById('edit-id').value,
            name: document.getElementById('edit-nombre').value,
            price: document.getElementById('edit-precio').value,
            stock: document.getElementById('edit-stock').value,
            categoria_id: document.getElementById('edit-cat').value
        });
    });

    // Formularios de Categorías
    document.getElementById('form-cat-agregar')?.addEventListener('submit', (e) => {
        e.preventDefault();
        gestionarAccion('crear_categoria', {
            id: document.getElementById('id_cat').value,
            nombre: document.getElementById('nombre_cat').value
        });
        e.target.reset();
    });

    document.getElementById('form-cat-editar')?.addEventListener('submit', (e) => {
        e.preventDefault();
        gestionarAccion('actualizar_categoria', {
            id: document.getElementById('edit-cat-id').value,
            nombre: document.getElementById('edit-cat-nombre').value
        });
    });

    // Buscador
    document.getElementById('inputBuscar')?.addEventListener('keyup', (e) => {
        const busqueda = e.target.value.toLowerCase();
        const filtrados = listaProductos.filter(p =>
            p.name.toLowerCase().includes(busqueda) || p.id.toString().includes(busqueda)
        );
        uiManager.dibujarTablaProductos(filtrados);
    });

    // Restaurar modal de producto si se cerró por crear categoría
    document.getElementById('modal-cat-agregar')?.addEventListener('hidden.bs.modal', () => {
        if (modalPadreId) {
            uiManager.abrirModal(modalPadreId);
            modalPadreId = null;
        }
    });
});
