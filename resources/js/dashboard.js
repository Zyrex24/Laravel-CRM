// Dashboard functionality
export function initDashboard() {
    return {
        loading: true,
        stats: {},
        apiStatus: {},
        systemInfo: {},
        recentActivities: [],
        recentLeads: [],

        async init() {
            await this.loadDashboard();
        },

        async loadDashboard() {
            try {
                const response = await fetch('/api/dashboard');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                this.stats = data.statistics || {};
                this.apiStatus = data.api_status || {};
                this.systemInfo = data.system_info || {};
                this.recentActivities = data.recent_activities || [];
                this.recentLeads = data.recent_leads || [];
                
                this.loading = false;
                console.log('Dashboard data loaded successfully:', data);
            } catch (error) {
                console.error('Failed to load dashboard:', error);
                this.loading = false;
                // Show user-friendly error
                alert('Failed to load dashboard data. Please check the console for details.');
            }
        },

        async testEndpoint(endpoint) {
            try {
                const response = await fetch(endpoint);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                const summary = {
                    endpoint: endpoint,
                    status: response.status,
                    dataCount: Array.isArray(data.data) ? data.data.length : (data.length || 'N/A'),
                    sample: JSON.stringify(data).substring(0, 200) + '...'
                };
                
                alert(`✅ ${endpoint} is working!\n\nStatus: ${summary.status}\nData Count: ${summary.dataCount}\nSample: ${summary.sample}`);
            } catch (error) {
                console.error(`Error testing ${endpoint}:`, error);
                alert(`❌ ${endpoint} failed!\n\nError: ${error.message}`);
            }
        },

        // Helper method to format currency
        formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        },

        // Helper method to format numbers
        formatNumber(number) {
            return new Intl.NumberFormat('en-US').format(number);
        }
    }
}