document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
      });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 1024 && 
          !e.target.closest('.sidebar') && 
          !e.target.closest('.sidebar-toggle') && 
          sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
      }
    });
    
    // User Dropdown
    const userBtn = document.querySelector('.user-btn');
    const userDropdown = document.querySelector('.dropdown-menu');
    
    if (userBtn && userDropdown) {
      userBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
      });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
      const dropdowns = document.querySelectorAll('.dropdown-menu.active, .notification-btn.active');
      dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
    });
    
    // Notification Dropdown
    const notificationBtn = document.querySelector('.notification-btn');
    if (notificationBtn) {
      notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationBtn.classList.toggle('active');
      });
    }
    
    // Global Search
    const globalSearch = document.querySelector('#globalSearch');
    if (globalSearch) {
      globalSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          const searchValue = globalSearch.value.trim();
          if (searchValue) {
            window.location.href = `admin_search.php?q=${encodeURIComponent(searchValue)}`;
          }
        }
      });
    }
    
    // Toast Messages
    const toastMessages = document.querySelectorAll('.toast-message');
    
    toastMessages.forEach(toast => {
      setTimeout(() => {
        toast.remove();
      }, 5000);
    });
    
    // Modal Functionality
    const modalToggles = document.querySelectorAll('[data-toggle="modal"]');
    const modalBackdrops = document.querySelectorAll('.modal-backdrop');
    const modalCloses = document.querySelectorAll('.modal-close');
    
    modalToggles.forEach(toggle => {
      toggle.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const targetModal = document.querySelector(targetId);
        
        if (targetModal) {
          targetModal.classList.add('active');
        }
      });
    });
    
    modalCloses.forEach(close => {
      close.addEventListener('click', function() {
        const modal = this.closest('.modal-backdrop');
        
        if (modal) {
          modal.classList.remove('active');
        }
      });
    });
    
    modalBackdrops.forEach(backdrop => {
      backdrop.addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.remove('active');
        }
      });
    });
    
    // Add animation class for fade-out
    const style = document.createElement('style');
    style.textContent = `
      .fade-out {
        animation: fadeOut 0.5s forwards;
      }
      
      @keyframes fadeOut {
        from {
          opacity: 1;
        }
        to {
          opacity: 0;
          transform: translateY(-20px);
        }
      }
    `;
    document.head.appendChild(style);
  
    // Product Form Image Preview
    const imageInput = document.querySelector('#product-image');
    const imagePreview = document.querySelector('#image-preview');
    
    if (imageInput && imagePreview) {
      imageInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
          const reader = new FileReader();
          
          reader.addEventListener('load', function() {
            imagePreview.src = this.result;
            imagePreview.style.display = 'block';
          });
          
          reader.readAsDataURL(file);
        }
      });
    }
  
    // Data table functionality
    const checkAll = document.querySelector('.check-all');
    
    if (checkAll) {
      const checkboxes = document.querySelectorAll('.row-checkbox');
      
      checkAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
        });
        
        updateBulkActions();
      });
      
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          updateBulkActions();
          
          // Update check-all status
          let allChecked = true;
          
          checkboxes.forEach(cb => {
            if (!cb.checked) {
              allChecked = false;
            }
          });
          
          checkAll.checked = allChecked;
        });
      });
      
      function updateBulkActions() {
        const bulkActionsContainer = document.querySelector('.bulk-actions');
        let hasChecked = false;
        
        checkboxes.forEach(checkbox => {
          if (checkbox.checked) {
            hasChecked = true;
          }
        });
        
        if (bulkActionsContainer) {
          bulkActionsContainer.style.display = hasChecked ? 'flex' : 'none';
        }
      }
    }
  
    // Filter toggle
    const filterToggle = document.querySelector('.filter-toggle');
    const filterPanel = document.querySelector('.filter-panel');
    
    if (filterToggle && filterPanel) {
      filterToggle.addEventListener('click', function() {
        filterPanel.classList.toggle('active');
      });
    }
  
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        
        form.classList.add('was-validated');
      }, false);
    });
  
    // Initialize charts if Chart.js is available and container exists
    if (typeof Chart !== 'undefined') {
      // Sales Chart
      const salesChartEl = document.getElementById('salesChart');
      if (salesChartEl) {
        const ctx = salesChartEl.getContext('2d');
        
        // Apply Chart.js Dark Mode
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#2d3748';
        
        // Sample data - would be replaced with real data
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
              label: 'Sales',
              data: [12, 19, 3, 5, 2, 3],
              borderColor: '#4f86f7',
              backgroundColor: 'rgba(79, 134, 247, 0.2)',
              tension: 0.4,
              fill: true
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  color: 'rgba(45, 55, 72, 0.5)'
                }
              },
              x: {
                grid: {
                  display: false
                }
              }
            }
          }
        });
      }
      
      // Category Chart
      const categoryChartEl = document.getElementById('categoryChart');
      if (categoryChartEl) {
        const ctx = categoryChartEl.getContext('2d');
        
        // Sample data - would be replaced with real data
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ['Fiction', 'Non-Fiction', 'Educational', 'Children', 'Other'],
            datasets: [{
              data: [30, 25, 20, 15, 10],
              backgroundColor: [
                '#2563eb',
                '#3b82f6',
                '#60a5fa',
                '#93c5fd',
                '#bfdbfe'
              ]
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });
      }
    }
  });