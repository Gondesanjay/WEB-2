/*!
* Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
*/
// 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Menampilkan flash message
    const statusParam = new URLSearchParams(window.location.search).get('status');
    const messageParam = new URLSearchParams(window.location.search).get('message');
    
    if (statusParam && messageParam) {
        displayFlashMessage(statusParam, messageParam);
    }
    
    // Highlight menu aktif
    highlightActiveMenu();
});

/**
 * Menampilkan flash message
 * @param {string} status Status pesan (success/error/warning)
 * @param {string} message Isi pesan
 */
function displayFlashMessage(status, message) {
    const alertClass = status === 'success' ? 'alert-success' : 
                      status === 'error' ? 'alert-danger' : 
                      status === 'warning' ? 'alert-warning' : 'alert-info';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const content = document.querySelector('.container-fluid');
    if (content) {
        content.insertBefore(alertDiv, content.firstChild);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
}

/**
 * Highlight menu yang aktif berdasarkan URL
 */
function highlightActiveMenu() {
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.nav-link');
    
    menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== 'index.php') {
            link.classList.add('active');
            
            // Jika submenu, buka parent collapse
            const parentCollapse = link.closest('.collapse');
            if (parentCollapse) {
                parentCollapse.classList.add('show');
                const parentToggle = document.querySelector(`[data-bs-target="#${parentCollapse.id}"]`);
                if (parentToggle) {
                    parentToggle.classList.remove('collapsed');
                    parentToggle.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
}