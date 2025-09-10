import './bootstrap';
import Alpine from 'alpinejs';
import { initDashboard } from './dashboard';
import { CRMCharts } from './charts';
import { initCRMModals } from './crm-modals';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Make dashboard function, charts, and modals globally available
window.dashboard = initDashboard;
window.CRMCharts = CRMCharts;
window.crmModals = initCRMModals;

// Global CRM utilities
window.CRM = {
    async apiCall(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const response = await fetch(endpoint, { ...defaultOptions, ...options });
        
        if (!response.ok) {
            throw new Error(`API call failed: ${response.status} ${response.statusText}`);
        }
        
        return await response.json();
    },
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
};

console.log('Laravel CRM frontend initialized');
