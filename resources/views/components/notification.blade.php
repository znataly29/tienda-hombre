@props(['type' => 'success', 'message' => ''])

<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div class="rounded-lg shadow-lg p-4 max-w-sm" 
         :class="{
            'bg-green-100 border border-green-400 text-green-700': type === 'success',
            'bg-red-100 border border-red-400 text-red-700': type === 'error',
            'bg-blue-100 border border-blue-400 text-blue-700': type === 'info',
            'bg-yellow-100 border border-yellow-400 text-yellow-700': type === 'warning'
         }">
        <div class="flex items-center gap-3">
            <span id="notification-icon"></span>
            <div>
                <p id="notification-message" class="font-semibold">{{ $message }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const notificationEl = document.getElementById('notification');
        const messageEl = document.getElementById('notification-message');
        const iconEl = document.getElementById('notification-icon');

        messageEl.textContent = mensaje;
        notificationEl.classList.remove('hidden');

        // Limpiar clases anteriores
        notificationEl.querySelector('div').className = 'rounded-lg shadow-lg p-4 max-w-sm';

        // Agregar clases según tipo
        const clases = {
            'success': 'bg-green-100 border border-green-400 text-green-700',
            'error': 'bg-red-100 border border-red-400 text-red-700',
            'info': 'bg-blue-100 border border-blue-400 text-blue-700',
            'warning': 'bg-yellow-100 border border-yellow-400 text-yellow-700'
        };

        notificationEl.querySelector('div').className += ' ' + (clases[tipo] || clases['success']);

        // Iconos según tipo
        const iconos = {
            'success': '✓',
            'error': '✕',
            'info': 'ℹ',
            'warning': '⚠'
        };

        iconEl.textContent = iconos[tipo] || '✓';
        iconEl.className = 'text-lg font-bold';

        // Auto-desaparecer después de 4 segundos
        setTimeout(() => {
            notificationEl.classList.add('hidden');
        }, 4000);
    }
</script>
