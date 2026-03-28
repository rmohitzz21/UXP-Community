/**
 * Admin Dashboard JavaScript
 * UX Pacific Community
 */

document.addEventListener('DOMContentLoaded', function() {
  // Sidebar Toggle (Mobile)
  const sidebar = document.getElementById('sidebar');
  const openBtn = document.getElementById('openSidebar');
  const closeBtn = document.getElementById('closeSidebar');
  const overlay = document.getElementById('sidebarOverlay');

  function openSidebar() {
    sidebar.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (openBtn) {
    openBtn.addEventListener('click', openSidebar);
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeSidebar);
  }

  if (overlay) {
    overlay.addEventListener('click', closeSidebar);
  }

  // Set active nav link based on current page
  const currentPage = window.location.pathname.split('/').pop() || 'admin.html';
  const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });

  // Search functionality
  const searchInput = document.querySelector('.topbar-search input');
  if (searchInput) {
    searchInput.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query) {
          console.log('Searching for:', query);
          // Implement search functionality here
        }
      }
    });
  }

  // Table row actions
  const actionButtons = document.querySelectorAll('.btn-action');
  actionButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const action = this.getAttribute('title');
      const row = this.closest('tr');
      const name = row.querySelector('.user-info span')?.textContent || 'Item';
      
      console.log(`${action} action for: ${name}`);
      // Implement specific actions here
    });
  });

  // Quick action buttons
  const quickActionBtns = document.querySelectorAll('.quick-action-btn');
  quickActionBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      // Add ripple effect
      const ripple = document.createElement('span');
      ripple.classList.add('ripple');
      this.appendChild(ripple);
      
      setTimeout(() => ripple.remove(), 600);
    });
  });

  // Notification badge click
  const notificationBtn = document.querySelector('.topbar-btn[aria-label="Notifications"]');
  if (notificationBtn) {
    notificationBtn.addEventListener('click', function() {
      console.log('Opening notifications panel');
      // Implement notifications panel
    });
  }

  // Form validation helpers
  window.validateEmail = function(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  };

  window.validatePhone = function(phone) {
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return re.test(phone);
  };

  // Status badge update helper
  window.updateStatus = function(element, status) {
    element.className = 'status-badge ' + status;
    element.textContent = status.charAt(0).toUpperCase() + status.slice(1);
  };

  // Initialize tooltips if Bootstrap tooltips are being used
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  if (tooltipTriggerList.length && typeof bootstrap !== 'undefined') {
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
  }

  // Confirmation dialog helper
  window.confirmAction = function(message, callback) {
    if (confirm(message)) {
      callback();
    }
  };

  // Format number with commas
  window.formatNumber = function(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  };

  // Format date
  window.formatDate = function(date) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
  };

  console.log('Admin dashboard initialized');
});
