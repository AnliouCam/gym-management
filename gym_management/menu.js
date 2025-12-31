// ==================== MENU BURGER MOBILE ====================

document.addEventListener('DOMContentLoaded', function() {
    // Créer le bouton burger
    const burgerBtn = document.createElement('button');
    burgerBtn.className = 'burger-btn';
    burgerBtn.innerHTML = '☰';
    burgerBtn.setAttribute('aria-label', 'Toggle menu');
    document.body.appendChild(burgerBtn);

    // Créer l'overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    const sidebar = document.querySelector('.sidebar');

    // Fonction pour ouvrir le menu
    function openMenu() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        burgerBtn.innerHTML = '✕';
        document.body.style.overflow = 'hidden'; // Empêcher le scroll du body
    }

    // Fonction pour fermer le menu
    function closeMenu() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        burgerBtn.innerHTML = '☰';
        document.body.style.overflow = ''; // Réactiver le scroll
    }

    // Toggle du menu au clic sur le bouton burger
    burgerBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('active')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // Fermer le menu au clic sur l'overlay
    overlay.addEventListener('click', closeMenu);

    // Fermer le menu au clic sur un lien de navigation
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Sur mobile uniquement
            if (window.innerWidth <= 768) {
                closeMenu();
            }
        });
    });

    // Fermer le menu si on redimensionne au-dessus de 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMenu();
        }
    });

    // Fermer le menu avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeMenu();
        }
    });
});
