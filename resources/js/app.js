import './bootstrap';

// Espera a que todo el HTML de la página se haya cargado
document.addEventListener('DOMContentLoaded', () => {
    
    // --- CÓDIGO PARA EL HEADER PRINCIPAL (Público) ---
    const hamburgerButton = document.getElementById('hamburger-button');
    const mobileMenu = document.getElementById('mobile-menu');

    // Revisa si el botón del header principal existe
    if (hamburgerButton && mobileMenu) {
        // Asigna el evento de clic para mostrar/ocultar el menú
        hamburgerButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- CÓDIGO PARA EL SIDEBAR DEL ADMIN (Menú Izquierdo) ---
    const sidebarToggle = document.getElementById('admin-sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    // Revisa si los elementos del admin existen
    if (sidebarToggle && sidebar && overlay) {
        
        // Función unificada para abrir/cerrar el sidebar
        const toggleAdminSidebar = () => {
            // Esta es la clase correcta para un menú izquierdo.
            // La alterna para mostrar u ocultar.
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        };

        // Asigna la misma función al botón y al overlay
        sidebarToggle.addEventListener('click', toggleAdminSidebar);
        overlay.addEventListener('click', toggleAdminSidebar);
    }
});