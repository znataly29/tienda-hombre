document.addEventListener('DOMContentLoaded', function(){
    const btnFiltrar = document.getElementById('filtrar');
    if(btnFiltrar){
        btnFiltrar.addEventListener('click', function(){
            const categoria = document.getElementById('f_categoria').value;
            const talla = document.getElementById('f_talla').value;
            const params = new URLSearchParams();
            if(categoria) params.append('categoria', categoria);
            if(talla) params.append('talla', talla);
            window.location = '/catalogo?'+params.toString();
        });
    }

    document.querySelectorAll('.agregar-carrito').forEach(btn => {
        btn.addEventListener('click', agregarAlCarrito);
    });

    async function agregarAlCarrito(e) {
        e.preventDefault();
        const btn = e.currentTarget || e.target;
        const id = btn.dataset.id;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const nombreProducto = btn.closest('div')?.querySelector('h2')?.textContent || 'Producto';
        
        try {
            const res = await fetch('/carrito/agregar', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ producto_id: id, cantidad: 1 })
            });

            if(res.ok){
                const response = await res.json();

                // Mostrar notificación de éxito
                mostrarNotificacion(`✓ "${nombreProducto}" agregado al carrito`, 'success');

                // Actualizar contador del carrito desde la respuesta
                const contadorEl = document.getElementById('nav-carrito-count');
                if (contadorEl) {
                    const total = response.total_items ?? null;
                    if (total !== null) {
                        contadorEl.textContent = total;
                        contadorEl.style.display = total > 0 ? 'inline-flex' : 'none';
                    }
                }

                // Esperar un poco antes de preguntar
                setTimeout(() => {
                    if(confirm('¿Deseas ir al carrito para revisar tu compra?')){
                        window.location.href = '/carrito';
                    }
                }, 800);
            } else {
                let text = 'No se pudo agregar el producto';
                try { const errorData = await res.json(); text = errorData.message || text; } catch(e) {}
                mostrarNotificacion('Error: ' + text, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarNotificacion('Error al agregar el producto al carrito', 'error');
        }
    }

    // Actualizar contador del carrito en la navegación (consulta web segura que usa sesión)
    async function actualizarContadorCarrito() {
        try {
            const res = await fetch('/carrito/count', { credentials: 'same-origin' });
            if(res.ok) {
                const data = await res.json();
                const contadorEl = document.getElementById('nav-carrito-count');
                if (contadorEl) {
                    contadorEl.textContent = data.count;
                    contadorEl.style.display = data.count > 0 ? 'inline-flex' : 'none';
                }
            }
        } catch (error) {
            console.error('Error al actualizar contador:', error);
        }
    }

    // Actualizar contador al cargar la página
    actualizarContadorCarrito();
});

