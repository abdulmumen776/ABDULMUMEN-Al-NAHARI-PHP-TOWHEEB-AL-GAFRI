import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Global notification system
window.showNotification = function(message, type = 'info') {
    const notification = { id: Date.now(), message, type, show: true };
    
    // Get the Alpine instance
    const alpineData = document.querySelector('[x-data]')?.__x.$data;
    
    if (alpineData && alpineData.notifications) {
        alpineData.notifications.push(notification);
        setTimeout(() => {
            notification.show = false;
            setTimeout(() => {
                alpineData.notifications = alpineData.notifications.filter(n => n.id !== notification.id);
            }, 300);
        }, 5000);
    }
};

// Global loading overlay
window.showLoading = function() {
    const alpineData = document.querySelector('[x-data]')?.__x.$data;
    if (alpineData) {
        alpineData.loading = true;
    }
};

window.hideLoading = function() {
    const alpineData = document.querySelector('[x-data]')?.__x.$data;
    if (alpineData) {
        alpineData.loading = false;
    }
};

// Chart.js global configuration
Chart.defaults.font.family = 'Cairo, sans-serif';
Chart.defaults.color = '#374151';
Chart.defaults.borderColor = '#E5E7EB';

// Dark mode support
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}

// API helper functions
window.api = {
    async get(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            window.showNotification('حدث خطأء في الاتصال بالخادم', 'error');
            throw error;
        }
    },

    async post(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            window.showNotification('حدث خطأء في إرسال البيانات', 'error');
            throw error;
        }
    },

    async put(url, data) {
        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            window.showNotification('حدث خطأء في تحديث البيانات', 'error');
            throw error;
        }
    },

    async delete(url) {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            window.showNotification('حدث خطأء في حذف البيانات', 'error');
            throw error;
        }
    }
};

// Utility functions
window.utils = {
    formatDate(date) {
        return new Date(date).toLocaleDateString('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },

    formatTime(date) {
        return new Date(date).toLocaleTimeString('ar-SA', {
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    formatDateTime(date) {
        return new Date(date).toLocaleString('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    formatNumber(number) {
        return new Intl.NumberFormat('ar-SA').format(number);
    },

    formatCurrency(amount) {
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: 'SAR'
        }).format(amount);
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), wait);
            };
            clearTimeout(timeout);
            timeout = later(...args);
        };
    },

    throttle(func, limit) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func(...args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
};

// Real-time updates
window.realtime = {
    connect() {
        if (window.Echo) {
            window.Echo.channel('dashboard-updates')
                .listen('PerformanceUpdated', (e) => {
                    this.updateMetrics(e.metrics);
                })
                .listen('AlertCreated', (e) => {
                    this.showAlert(e.alert);
                })
                .listen('SystemStatus', (e) => {
                    this.updateSystemStatus(e.status);
                });
        }
    },

    updateMetrics(metrics) {
        // Update dashboard metrics
        const alpineData = document.querySelector('[x-data="dashboard()"]')?.__x.$data;
        if (alpineData) {
            alpineData.statistics = { ...alpineData.statistics, ...metrics };
        }
    },

    showAlert(alert) {
        window.showNotification(alert.message, alert.type === 'critical' ? 'error' : 'warning');
    },

    updateSystemStatus(status) {
        const statusIndicators = document.querySelectorAll('.status-indicator');
        statusIndicators.forEach(indicator => {
            indicator.className = `status-indicator status-${status}`;
        });
    }
};

// Initialize real-time connection
document.addEventListener('DOMContentLoaded', () => {
    window.realtime.connect();
});

// Chart helper functions
window.charts = {
    createLineChart(ctx, data, options = {}) {
        return new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    }
                },
                ...options
            }
        });
    },

    createBarChart(ctx, data, options = {}) {
        return new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    }
                },
                ...options
            }
        });
    },

    createDoughnutChart(ctx, data, options = {}) {
        return new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    }
                },
                ...options
            }
        });
    },

    createPieChart(ctx, data, options = {}) {
        return new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Cairo'
                            }
                        }
                    }
                },
                ...options
            }
        });
    }
};

// Form validation
window.forms = {
    validate(form) {
        const formData = new FormData(form);
        const errors = {};
        
        // Basic validation rules
        for (let [key, value] of formData.entries()) {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                const required = field.hasAttribute('required');
                const type = field.type;
                const min = field.getAttribute('min');
                const max = field.getAttribute('max');
                
                if (required && !value) {
                    errors[key] = 'هذا الحقل مطلوب';
                }
                
                if (type === 'email' && value && !this.isValidEmail(value)) {
                    errors[key] = 'البريد الإلكتروني غير صحيح';
                }
                
                if (type === 'tel' && value && !this.isValidPhone(value)) {
                    errors[key] = 'رقم الهاتف غير صحيح';
                }
                
                if (min && value && parseFloat(value) < parseFloat(min)) {
                    errors[key] = `القيمة يجب أن تكون على الأقل ${min}`;
                }
                
                if (max && value && parseFloat(value) > parseFloat(max)) {
                    errors[key] = `القيمة يجب أن تكون على الأكثر ${max}`;
                }
            }
        }
        
        return errors;
    },
    
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    
    isValidPhone(phone) {
        return /^[\+]?[1-9][\d]{3,14}$/.test(phone);
    },
    
    showErrors(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        
        // Show new errors
        Object.entries(errors).forEach(([field, message]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                errorDiv.textContent = message;
                input.parentNode.appendChild(errorDiv);
                input.classList.add('border-red-500');
            }
        });
    },
    
    clearErrors(form) {
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });
    }
};

// Responsive table helper
window.tables = {
    responsive() {
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive overflow-x-auto';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        });
    }
};

// Initialize responsive tables
document.addEventListener('DOMContentLoaded', () => {
    window.tables.responsive();
});

// Auto-resize charts
window.addEventListener('resize', () => {
    const charts = Chart.instances;
    charts.forEach(chart => {
        chart.resize();
    });
});

// Print support
window.print = {
    init() {
        const printButton = document.createElement('button');
        printButton.className = 'hidden print:visible bg-blue-600 text-white px-4 py-2 rounded-lg';
        printButton.textContent = 'طباعة';
        printButton.onclick = () => {
            window.print();
        };
        document.body.appendChild(printButton);
        
        // Add print styles
        const printStyles = document.createElement('style');
        printStyles.textContent = `
            @media print {
                .no-print { display: none !important; }
                .print-only { display: block !important; }
                body { font-size: 12pt; }
                .sidebar { display: none !important; }
                .main-content { margin: 0 !important; }
                .card { break-inside: avoid; }
            }
        `;
        document.head.appendChild(printStyles);
    }
};

// Initialize print support
document.addEventListener('DOMContentLoaded', () => {
    window.print.init();
});
