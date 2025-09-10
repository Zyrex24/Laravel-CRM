// CRM Modal Components
export function initCRMModals() {
    return {
        modals: {
            contact: false,
            lead: false,
            activity: false,
            organization: false
        },
        
        forms: {
            contact: {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                title: '',
                company: '',
                organization_id: '',
                notes: '',
                source: 'website'
            },
            lead: {
                title: '',
                description: '',
                status: 'new',
                source: '',
                priority: 'medium',
                value: '',
                expected_close_date: '',
                probability: 50,
                contact_id: '',
                organization_id: '',
                notes: ''
            },
            activity: {
                title: '',
                description: '',
                type: 'call',
                status: 'pending',
                priority: 'medium',
                due_date: '',
                contact_id: '',
                lead_id: '',
                organization_id: ''
            },
            organization: {
                name: '',
                website: '',
                industry: '',
                employees: '',
                annual_revenue: '',
                description: '',
                phone: '',
                email: '',
                address: {
                    street: '',
                    city: '',
                    state: '',
                    zip: ''
                }
            }
        },
        
        loading: {
            contact: false,
            lead: false,
            activity: false,
            organization: false
        },

        // Open modal
        openModal(type) {
            this.modals[type] = true;
            this.resetForm(type);
        },

        // Close modal
        closeModal(type) {
            this.modals[type] = false;
            this.resetForm(type);
        },

        // Reset form data
        resetForm(type) {
            if (type === 'contact') {
                this.forms.contact = {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    title: '',
                    company: '',
                    organization_id: '',
                    notes: '',
                    source: 'website'
                };
            } else if (type === 'lead') {
                this.forms.lead = {
                    title: '',
                    description: '',
                    status: 'new',
                    source: '',
                    priority: 'medium',
                    value: '',
                    expected_close_date: '',
                    probability: 50,
                    contact_id: '',
                    organization_id: '',
                    notes: ''
                };
            } else if (type === 'activity') {
                this.forms.activity = {
                    title: '',
                    description: '',
                    type: 'call',
                    status: 'pending',
                    priority: 'medium',
                    due_date: '',
                    contact_id: '',
                    lead_id: '',
                    organization_id: ''
                };
            } else if (type === 'organization') {
                this.forms.organization = {
                    name: '',
                    website: '',
                    industry: '',
                    employees: '',
                    annual_revenue: '',
                    description: '',
                    phone: '',
                    email: '',
                    address: {
                        street: '',
                        city: '',
                        state: '',
                        zip: ''
                    }
                };
            }
        },

        // Submit form
        async submitForm(type) {
            this.loading[type] = true;
            
            try {
                const endpoint = `/api/${type}s`;
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.forms[type])
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                // Show success message
                this.showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} created successfully!`, 'success');
                
                // Close modal
                this.closeModal(type);
                
                // Refresh dashboard data
                if (this.loadDashboardData) {
                    await this.loadDashboardData();
                }
                
                return result;
                
            } catch (error) {
                console.error(`Error creating ${type}:`, error);
                this.showNotification(`Failed to create ${type}. Please try again.`, 'error');
            } finally {
                this.loading[type] = false;
            }
        },

        // Show notification
        showNotification(message, type = 'info') {
            // Create a simple notification (you can enhance this)
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg transition-opacity ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        },

        // Get current date in YYYY-MM-DD format
        getCurrentDate() {
            return new Date().toISOString().split('T')[0];
        },

        // Get current datetime in YYYY-MM-DDTHH:MM format
        getCurrentDateTime() {
            const now = new Date();
            return now.toISOString().slice(0, 16);
        }
    }
}