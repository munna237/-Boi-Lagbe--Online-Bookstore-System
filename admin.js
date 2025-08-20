// Get current page from URL
const getCurrentPage = () => {
    const path = window.location.pathname;
    const page = path.split('/').pop().replace('admin_', '').replace('.php', '');
    return page || 'dashboard';
};

// Update page title and breadcrumb
const updatePageTitle = () => {
    const page = getCurrentPage();
    const title = page.charAt(0).toUpperCase() + page.slice(1);
    
    // Update page title
    const pageTitle = document.querySelector('.page-title');
    if (pageTitle) {
        pageTitle.textContent = title;
    }
    
    // Update breadcrumb
    const breadcrumb = document.querySelector('.breadcrumb');
    if (breadcrumb) {
        breadcrumb.innerHTML = `
            <div class="breadcrumb-item">
                <i class="fas fa-home"></i>
                <a href="admin_dashboard.php">Dashboard</a>
            </div>
            ${page !== 'dashboard' ? `
                <span class="breadcrumb-separator">/</span>
                <div class="breadcrumb-item">
                    <span>${title}</span>
                </div>
            ` : ''}
        `;
    }
};

// Update active sidebar link
const updateActiveSidebarLink = () => {
    const currentPage = getCurrentPage();
    const sidebarLinks = document.querySelectorAll('.nav-link');
    
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes(currentPage)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
};

// Handle mobile menu toggle
const initMobileMenu = () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }
};

// Initialize all functionality
const initAdmin = () => {
    updatePageTitle();
    updateActiveSidebarLink();
    initMobileMenu();
};

// Run on page load
document.addEventListener('DOMContentLoaded', initAdmin); 