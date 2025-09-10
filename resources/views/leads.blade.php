<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Leads - Laravel CRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50" x-data="leadsPage()">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-xl font-bold text-gray-900">Laravel CRM</h1>
                            <p class="text-xs text-gray-500">Lead Management</p>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="/" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Dashboard</a>
                        <a href="/contacts" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Contacts</a>
                        <a href="/leads" class="text-blue-600 border-b-2 border-blue-600 px-1 pb-4 pt-1 text-sm font-medium">Leads</a>
                        <a href="/activities" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Activities</a>
                        <a href="/reports" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Reports</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="flex-1 max-w-lg">
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="search" placeholder="Search leads..." 
                                   x-model="searchQuery"
                                   @input="searchLeads()"
                                   class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <!-- Add Lead Button -->
                    <button @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 relative z-10" style="pointer-events: auto; cursor: pointer;">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Lead
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Leads</h2>
            <p class="text-gray-600 mt-1">Track and manage your sales opportunities</p>
        </div>

        <!-- Pipeline Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">New Leads</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getLeadsByStatus('new').length"></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Qualified</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getLeadsByStatus('qualified').length"></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Proposal</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getLeadsByStatus('proposal').length"></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-green-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Won</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getLeadsByStatus('won').length"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Status:</label>
                    <select x-model="filters.status" @change="applyFilters()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All Status</option>
                        <option value="new">New</option>
                        <option value="qualified">Qualified</option>
                        <option value="proposal">Proposal</option>
                        <option value="won">Won</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Priority:</label>
                    <select x-model="filters.priority" @change="applyFilters()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select x-model="sortBy" @change="sortLeads()" class="text-sm border-gray-300 rounded-md">
                        <option value="title">Title</option>
                        <option value="value">Value</option>
                        <option value="probability">Probability</option>
                        <option value="expected_close_date">Close Date</option>
                        <option value="created_at">Date Added</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
            <span class="ml-3 text-gray-600">Loading leads...</span>
        </div>

        <!-- Leads Grid -->
        <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="lead in filteredLeads" :key="lead.id">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer" @click="viewLead(lead)">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-2" x-text="lead.title"></h3>
                            <p class="text-sm text-gray-600 mb-3" x-text="lead.description"></p>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                  :class="{
                                      'bg-red-100 text-red-800': lead.priority === 'high',
                                      'bg-yellow-100 text-yellow-800': lead.priority === 'medium',
                                      'bg-green-100 text-green-800': lead.priority === 'low'
                                  }"
                                  x-text="lead.priority"></span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Value:</span>
                            <span class="font-medium text-gray-900" x-text="'$' + lead.value.toLocaleString()"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Probability:</span>
                            <span class="font-medium text-gray-900" x-text="lead.probability + '%'"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Expected Close:</span>
                            <span class="font-medium text-gray-900" x-text="formatDate(lead.expected_close_date)"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Source:</span>
                            <span class="font-medium text-gray-900" x-text="lead.source"></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                              :class="{
                                  'bg-blue-100 text-blue-800': lead.status === 'new',
                                  'bg-yellow-100 text-yellow-800': lead.status === 'qualified',
                                  'bg-purple-100 text-purple-800': lead.status === 'proposal',
                                  'bg-green-100 text-green-800': lead.status === 'won',
                                  'bg-red-100 text-red-800': lead.status === 'lost'
                              }"
                              x-text="lead.status"></span>
                        <div class="flex space-x-2">
                            <button @click.stop="editLead(lead)" class="text-blue-600 hover:text-blue-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button @click.stop="deleteLead(lead)" class="text-red-600 hover:text-red-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && filteredLeads.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No leads found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding your first lead.</p>
            <div class="mt-6">
                <button @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 relative z-10" style="pointer-events: auto; cursor: pointer;">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Lead
                </button>
            </div>
        </div>
    </main>

    <script>
        function leadsPage() {
            return {
                loading: true,
                leads: [],
                filteredLeads: [],
                searchQuery: '',
                sortBy: 'title',
                filters: {
                    status: '',
                    priority: ''
                },
                loading: {
                    contact: false,
                    lead: false,
                    activity: false,
                    editLead: false
                },
                currentLead: null,
                currentContact: null,
                contactToDelete: null,
                editForms: {
                    lead: {
                        title: '',
                        description: '',
                        status: 'new',
                        priority: 'medium',
                        value: '',
                        probability: '',
                        expected_close_date: '',
                        source: '',
                        notes: ''
                    },
                    contact: {
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone: '',
                        company: '',
                        source: 'website',
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
                        lead_id: ''
                    }
                },
                modalLoading: {
                    contact: false,
                    lead: false,
                    activity: false
                },
                modals: {
                    contact: false,
                    lead: false,
                    activity: false,
                    viewLead: false,
                    editLead: false
                },
                forms: {
                    contact: {
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone: '',
                        title: '',
                        company: '',
                        source: 'website',
                        notes: ''
                    },
                    lead: {
                        title: '',
                        description: '',
                        status: 'new',
                        priority: 'medium',
                        value: '',
                        probability: '',
                        expected_close_date: '',
                        source: '',
                        notes: ''
                    },
                    activity: {
                        title: '',
                        type: 'call',
                        priority: 'medium',
                        due_date: '',
                        description: ''
                    }
                },

                async init() {
                    console.log('🚀 Initializing Leads Page...');
                    await this.loadLeads();
                    this.applyFilters();
                    console.log('✅ Leads page initialized');
                },

                async loadLeads() {
                    try {
                        console.log('📋 Loading leads...');
                        const response = await fetch('/api/leads');
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.leads = data.leads || this.generateMockLeads();
                        } else {
                            // Fallback to mock data
                            this.leads = this.generateMockLeads();
                        }
                        
                        console.log('📋 Leads loaded:', this.leads.length);
                    } catch (error) {
                        console.error('❌ Failed to load leads:', error);
                        // Use mock data as fallback
                        this.leads = this.generateMockLeads();
                    } finally {
                        this.loading = false;
                    }
                },

                generateMockLeads() {
                    return [
                        { id: 1, title: 'Enterprise Software Deal', description: 'Large enterprise looking for CRM solution', value: 50000, probability: 75, status: 'qualified', priority: 'high', source: 'Website', expected_close_date: '2024-02-15', created_at: '2024-01-15' },
                        { id: 2, title: 'Small Business Package', description: 'Local business needs basic CRM features', value: 5000, probability: 60, status: 'proposal', priority: 'medium', source: 'Referral', expected_close_date: '2024-02-01', created_at: '2024-01-14' },
                        { id: 3, title: 'Startup Integration', description: 'Tech startup wants API integration', value: 15000, probability: 40, status: 'new', priority: 'low', source: 'Social Media', expected_close_date: '2024-03-01', created_at: '2024-01-13' },
                        { id: 4, title: 'Government Contract', description: 'Government agency RFP response', value: 100000, probability: 30, status: 'qualified', priority: 'high', source: 'Direct', expected_close_date: '2024-04-15', created_at: '2024-01-12' },
                        { id: 5, title: 'Healthcare Solution', description: 'Hospital management system', value: 75000, probability: 85, status: 'won', priority: 'high', source: 'Partner', expected_close_date: '2024-01-30', created_at: '2024-01-11' }
                    ];
                },

                searchLeads() {
                    this.applyFilters();
                },

                applyFilters() {
                    let filtered = [...this.leads];

                    // Apply search filter
                    if (this.searchQuery.trim()) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(lead => 
                            lead.title.toLowerCase().includes(query) ||
                            lead.description.toLowerCase().includes(query) ||
                            lead.source.toLowerCase().includes(query)
                        );
                    }

                    // Apply status filter
                    if (this.filters.status) {
                        filtered = filtered.filter(lead => lead.status === this.filters.status);
                    }

                    // Apply priority filter
                    if (this.filters.priority) {
                        filtered = filtered.filter(lead => lead.priority === this.filters.priority);
                    }

                    this.filteredLeads = filtered;
                    this.sortLeads();
                },

                sortLeads() {
                    this.filteredLeads.sort((a, b) => {
                        switch (this.sortBy) {
                            case 'title':
                                return a.title.localeCompare(b.title);
                            case 'value':
                                return b.value - a.value;
                            case 'probability':
                                return b.probability - a.probability;
                            case 'expected_close_date':
                                return new Date(a.expected_close_date) - new Date(b.expected_close_date);
                            case 'created_at':
                                return new Date(b.created_at) - new Date(a.created_at);
                            default:
                                return 0;
                        }
                    });
                },

                getLeadsByStatus(status) {
                    return this.leads.filter(lead => lead.status === status);
                },

                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString();
                },

                viewLead(lead) {
                    this.currentLead = lead;
                    this.modals.viewLead = true;
                },

                showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg transition-opacity ${
                        type === 'success' ? 'bg-green-500 text-white' :
                        type === 'error' ? 'bg-red-500 text-white' :
                        'bg-blue-500 text-white'
                    }`;
                    notification.textContent = message;
                    document.body.appendChild(notification);
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        setTimeout(() => {
                            if (notification.parentNode) {
                                document.body.removeChild(notification);
                            }
                        }, 300);
                    }, 3000);
                },

                editLead(lead) {
                    this.currentLead = lead;
                    // Populate edit form with current lead data
                    this.editForms.lead = {
                        id: lead.id,
                        title: lead.title,
                        description: lead.description,
                        status: lead.status,
                        priority: lead.priority,
                        value: lead.value,
                        probability: lead.probability,
                        expected_close_date: lead.expected_close_date,
                        source: lead.source,
                        notes: lead.notes
                    };
                    this.closeModal('viewLead'); // Close view modal if open
                    this.modals.editLead = true;
                },

                deleteLead(lead) {
                    if (confirm(`Are you sure you want to delete ${lead.title}?`)) {
                        console.log('Deleting lead:', lead);
                        this.leads = this.leads.filter(l => l.id !== lead.id);
                        this.applyFilters();
                    }
                },

                openAddModal() {
                    this.modals.lead = true;
                },

                updateLead() {
                    this.loading.editLead = true;
                    
                    // Find and update the lead in the leads array
                    const leadIndex = this.leads.findIndex(l => l.id === this.editForms.lead.id);
                    if (leadIndex !== -1) {
                        // Update the lead with new data
                        this.leads[leadIndex] = {
                            ...this.leads[leadIndex],
                            ...this.editForms.lead,
                            updated_at: new Date().toISOString()
                        };
                        
                        // Update currentLead if it's the same lead
                        if (this.currentLead && this.currentLead.id === this.editForms.lead.id) {
                            this.currentLead = this.leads[leadIndex];
                        }
                        
                        // Apply filters to refresh the display
                        this.applyFilters();
                        
                        // Close modal and reset form
                        this.closeModal('editLead');
                        
                        // Show success message (you can replace with a toast notification)
                        this.closeModal('editLead');
                        this.showNotification('Lead updated successfully!', 'success');
                    }
                    
                    this.loading.editLead = false;
                },

                // Modal Management
                closeModal(type) {
                    this.modals[type] = false;
                    this.resetForm(type);
                },

                resetForm(type) {
                    const defaultForms = {
                        contact: {
                            first_name: '',
                            last_name: '',
                            email: '',
                            phone: '',
                            title: '',
                            company: '',
                            source: 'website',
                            notes: ''
                        },
                        lead: {
                            title: '',
                            description: '',
                            status: 'new',
                            priority: 'medium',
                            value: '',
                            probability: '',
                            expected_close_date: '',
                            source: '',
                            notes: ''
                        },
                        activity: {
                            title: '',
                            type: 'call',
                            priority: 'medium',
                            due_date: '',
                            description: ''
                        }
                    };
                    this.forms[type] = { ...defaultForms[type] };
                },

                async submitForm(type) {
                    this.modalLoading[type] = true;
                    
                    try {
                        let created = null;
                        try {
                            const response = await fetch(`/api/${type}s`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(this.forms[type])
                            });

                            if (response.ok) {
                                const data = await response.json();
                                created = data[type] || data;
                            }
                        } catch (apiError) {
                            console.warn('API create failed, will fallback to local creation:', apiError);
                        }

                        if (!created && type === 'lead') {
                            created = {
                                id: Date.now(),
                                ...this.forms.lead,
                                created_at: new Date().toISOString()
                            };
                        }

                        if (!created) {
                            throw new Error(`Failed to create ${type}`);
                        }
                        
                        if (type === 'lead') {
                            this.leads.unshift(created);
                            this.applyFilters();
                        }
                        
                        this.closeModal(type);
                        this.showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} created successfully!`, 'success');
                    } catch (error) {
                        console.error(`Error creating ${type}:`, error);
                        this.showNotification(`Failed to create ${type}. Please try again.`, 'error');
                    } finally {
                        this.modalLoading[type] = false;
                    }
                }
            }
        }
    </script>

    @include('partials.modals')
</body>
</html>