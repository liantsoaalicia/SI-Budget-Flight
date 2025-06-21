let mobileSidebarOpen = false;
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    setupEventListeners();
});

function initializeApp() {
    console.log('Budget Management App initialized');
    
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.classList.add('fade-in');
    }
    
    handleResponsiveLayout();
    window.addEventListener('resize', handleResponsiveLayout);
}

function setupEventListeners() {
    // Sidebar toggle button (réactivé)
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    // Mobile toggle button
    const mobileToggle = document.querySelector('.mobile-toggle');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleMobileSidebar);
    }
    
    // Close mobile sidebar when clicking outside
    document.addEventListener('click', function(e) {
        const sidebar = document.querySelector('.app-sidebar');
        const mobileToggle = document.querySelector('.mobile-toggle');
        
        if (window.innerWidth <= 768 && 
            mobileSidebarOpen && 
            !sidebar.contains(e.target) && 
            !mobileToggle.contains(e.target)) {
            closeMobileSidebar();
        }
    });
    
    // Handle escape key for mobile sidebar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileSidebarOpen) {
            closeMobileSidebar();
        }
    });
    
    // Add smooth scrolling to anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Toggle desktop sidebar (réactivé)
function toggleSidebar() {
    const sidebar = document.querySelector('.app-sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle i');
    if (!sidebar || !toggleBtn) return;
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');
    // Update toggle button icon
    if (isCollapsed) {
        toggleBtn.classList.remove('fa-chevron-left');
        toggleBtn.classList.add('fa-chevron-right');
    } else {
        toggleBtn.classList.remove('fa-chevron-right');
        toggleBtn.classList.add('fa-chevron-left');
    }
}

// Toggle mobile sidebar
function toggleMobileSidebar() {
    const sidebar = document.querySelector('.app-sidebar');
    if (!sidebar) return;
    
    if (mobileSidebarOpen) {
        closeMobileSidebar();
    } else {
        openMobileSidebar();
    }
}

// Open mobile sidebar
function openMobileSidebar() {
    const sidebar = document.querySelector('.app-sidebar');
    if (!sidebar) return;
    
    sidebar.classList.add('show');
    mobileSidebarOpen = true;
    
    // Add overlay
    createOverlay();
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Close mobile sidebar
function closeMobileSidebar() {
    const sidebar = document.querySelector('.app-sidebar');
    if (!sidebar) return;
    
    sidebar.classList.remove('show');
    mobileSidebarOpen = false;
    
    // Remove overlay
    removeOverlay();
    
    // Restore body scroll
    document.body.style.overflow = '';
}

// Create overlay for mobile sidebar
function createOverlay() {
    if (document.querySelector('.sidebar-overlay')) return;
    
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
        animation: fadeIn 0.3s ease;
    `;
    
    overlay.addEventListener('click', closeMobileSidebar);
    document.body.appendChild(overlay);
}

// Remove overlay
function removeOverlay() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Handle responsive layout changes
function handleResponsiveLayout() {
    const sidebar = document.querySelector('.app-sidebar');
    if (!sidebar) return;
    
    if (window.innerWidth <= 768) {
        // Mobile mode
        if (mobileSidebarOpen) {
            sidebar.classList.add('show');
        } else {
            sidebar.classList.remove('show');
        }
        // Toujours déplié sur mobile
        sidebar.classList.remove('collapsed');
    } else {
        // Desktop mode
        sidebar.classList.remove('show');
        mobileSidebarOpen = false;
        removeOverlay();
        document.body.style.overflow = '';
        
        // Ne touche pas à l'état collapsed sur desktop
    }
}

// Apply stored preferences
function applyStoredPreferences() {
    // Ne fait rien
}

// Utility functions
const Utils = {
    // Debounce function for performance
    debounce: function(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    },
    
    // Format currency
    formatCurrency: function(amount, currency = 'EUR') {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },
    
    // Format date
    formatDate: function(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat('fr-FR', {...defaultOptions, ...options}).format(new Date(date));
    },
    
    // Show notification
    showNotification: function(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Add styles if not already present
        if (!document.querySelector('#notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                .notification {
                    position: fixed;
                    top: 90px;
                    right: 20px;
                    z-index: 10000;
                    min-width: 300px;
                    padding: 1rem;
                    border-radius: var(--radius-md);
                    box-shadow: var(--shadow-lg);
                    animation: slideInRight 0.3s ease;
                    background: white;
                    border-left: 4px solid;
                }
                .notification-info { border-left-color: var(--info-color); }
                .notification-success { border-left-color: var(--success-color); }
                .notification-warning { border-left-color: var(--warning-color); }
                .notification-error { border-left-color: var(--danger-color); }
                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                }
                .notification-close {
                    background: none;
                    border: none;
                    color: var(--text-light);
                    cursor: pointer;
                    margin-left: auto;
                    padding: 0.25rem;
                }
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(styles);
        }
        
        document.body.appendChild(notification);
        
        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideInRight 0.3s ease reverse';
                    setTimeout(() => notification.remove(), 300);
                }
            }, duration);
        }
    },
    
    // Get notification icon based on type
    getNotificationIcon: function(type) {
        const icons = {
            info: 'info-circle',
            success: 'check-circle',
            warning: 'exclamation-triangle',
            error: 'exclamation-circle'
        };
        return icons[type] || 'info-circle';
    },
    
    // Confirm dialog
    confirm: function(message, callback) {
        const modal = document.createElement('div');
        modal.className = 'confirm-modal';
        modal.innerHTML = `
            <div class="confirm-overlay">
                <div class="confirm-dialog">
                    <div class="confirm-header">
                        <h3>Confirmation</h3>
                    </div>
                    <div class="confirm-body">
                        <p>${message}</p>
                    </div>
                    <div class="confirm-footer">
                        <button class="btn btn-cancel">Annuler</button>
                        <button class="btn btn-primary btn-confirm">Confirmer</button>
                    </div>
                </div>
            </div>
        `;
        
        // Add styles if not already present
        if (!document.querySelector('#confirm-styles')) {
            const styles = document.createElement('style');
            styles.id = 'confirm-styles';
            styles.textContent = `
                .confirm-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    z-index: 10001;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    animation: fadeIn 0.3s ease;
                }
                .confirm-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .confirm-dialog {
                    background: white;
                    border-radius: var(--radius-lg);
                    box-shadow: var(--shadow-xl);
                    min-width: 400px;
                    max-width: 90vw;
                    animation: scaleIn 0.3s ease;
                }
                .confirm-header {
                    padding: 1.5rem 1.5rem 0;
                }
                .confirm-header h3 {
                    margin: 0;
                    color: var(--text-primary);
                }
                .confirm-body {
                    padding: 1rem 1.5rem;
                }
                .confirm-footer {
                    padding: 0 1.5rem 1.5rem;
                    display: flex;
                    gap: 1rem;
                    justify-content: flex-end;
                }
                @keyframes scaleIn {
                    from { transform: scale(0.9); opacity: 0; }
                    to { transform: scale(1); opacity: 1; }
                }
            `;
            document.head.appendChild(styles);
        }
        
        document.body.appendChild(modal);
        
        // Handle buttons
        modal.querySelector('.btn-cancel').addEventListener('click', () => {
            modal.remove();
            if (callback) callback(false);
        });
        
        modal.querySelector('.btn-confirm').addEventListener('click', () => {
            modal.remove();
            if (callback) callback(true);
        });
        
        // Handle overlay click
        modal.querySelector('.confirm-overlay').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                modal.remove();
                if (callback) callback(false);
            }
        });
        
        // Handle escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', handleEscape);
                if (callback) callback(false);
            }
        };
        document.addEventListener('keydown', handleEscape);
    },
    
    // Loading spinner
    showLoading: function(container) {
        const loading = document.createElement('div');
        loading.className = 'loading-spinner';
        loading.innerHTML = `
            <div class="spinner">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        `;
        
        if (!document.querySelector('#loading-styles')) {
            const styles = document.createElement('style');
            styles.id = 'loading-styles';
            styles.textContent = `
                .loading-spinner {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 2rem;
                    color: var(--primary-color);
                }
                .spinner i {
                    font-size: 2rem;
                }
            `;
            document.head.appendChild(styles);
        }
        
        if (container) {
            container.appendChild(loading);
        }
        
        return loading;
    },
    
    // Hide loading
    hideLoading: function(loading) {
        if (loading && loading.parentElement) {
            loading.remove();
        }
    }
};

// Form validation utilities
const FormValidator = {
    // Validate required fields
    validateRequired: function(value) {
        return value && value.toString().trim().length > 0;
    },
    
    // Validate email
    validateEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    // Validate number
    validateNumber: function(value) {
        return !isNaN(value) && !isNaN(parseFloat(value));
    },
    
    // Validate positive number
    validatePositiveNumber: function(value) {
        return this.validateNumber(value) && parseFloat(value) > 0;
    },
    
    // Show field error
    showFieldError: function(field, message) {
        this.clearFieldError(field);
        
        const error = document.createElement('div');
        error.className = 'field-error';
        error.textContent = message;
        error.style.cssText = `
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        `;
        
        field.parentElement.appendChild(error);
        field.classList.add('error');
        
        if (!document.querySelector('#field-error-styles')) {
            const styles = document.createElement('style');
            styles.id = 'field-error-styles';
            styles.textContent = `
                .form-control.error {
                    border-color: var(--danger-color);
                    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
                }
            `;
            document.head.appendChild(styles);
        }
    },
    
    // Clear field error
    clearFieldError: function(field) {
        const error = field.parentElement.querySelector('.field-error');
        if (error) {
            error.remove();
        }
        field.classList.remove('error');
    }
};

// Export utilities for use in other scripts
window.BudgetApp = {
    Utils,
    FormValidator,
    toggleSidebar,
    toggleMobileSidebar
};

// Console welcome message
console.log('%c💰 Budget Management App %c- Ready!', 
    'background: #3b82f6; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold;',
    'color: #64748b; padding: 4px 0;'
);