<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contacts - Laravel CRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50" x-data="contactsPage()">
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
                            <p class="text-xs text-gray-500">Contact Management</p>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="/" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Dashboard</a>
                        <a href="/contacts" class="text-blue-600 border-b-2 border-blue-600 px-1 pb-4 pt-1 text-sm font-medium">Contacts</a>
                        <a href="/leads" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Leads</a>
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
                            <input type="search" placeholder="Search contacts..." 
                                   x-model="searchQuery"
                                   @input="searchContacts()"
                                   class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <!-- Add Contact Button -->
                    <button @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 relative z-10" style="pointer-events: auto; cursor: pointer;">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Contact
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Contacts</h2>
            <p class="text-gray-600 mt-1">Manage your customer contacts and relationships</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Filter by:</label>
                    <select x-model="filters.source" @change="applyFilters()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All Sources</option>
                        <option value="website">Website</option>
                        <option value="referral">Referral</option>
                        <option value="social">Social Media</option>
                        <option value="email">Email Campaign</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select x-model="sortBy" @change="sortContacts()" class="text-sm border-gray-300 rounded-md">
                        <option value="name">Name</option>
                        <option value="company">Company</option>
                        <option value="created_at">Date Added</option>
                        <option value="last_contact">Last Contact</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="toggleView()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg x-show="viewMode === 'grid'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <svg x-show="viewMode === 'list'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="ml-1" x-text="viewMode === 'grid' ? 'List View' : 'Grid View'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading contacts...</span>
        </div>

        <!-- Contacts Grid View -->
        <div x-show="!loading && viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="contact in filteredContacts" :key="contact.id">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer" @click="viewContact(contact)">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-medium text-lg" x-text="getInitials(contact.first_name, contact.last_name)"></span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-lg font-medium text-gray-900 truncate" x-text="`${contact.first_name} ${contact.last_name}`"></p>
                            <p class="text-sm text-gray-500 truncate" x-text="contact.title || 'No title'"></p>
                            <p class="text-sm text-gray-500 truncate" x-text="contact.company || 'No company'"></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span x-text="contact.email"></span>
                        </div>
                        <div x-show="contact.phone" class="flex items-center text-sm text-gray-500 mt-1">
                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span x-text="contact.phone"></span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                              :class="{
                                  'bg-green-100 text-green-800': contact.source === 'website',
                                  'bg-blue-100 text-blue-800': contact.source === 'referral',
                                  'bg-purple-100 text-purple-800': contact.source === 'social',
                                  'bg-orange-100 text-orange-800': contact.source === 'email',
                                  'bg-gray-100 text-gray-800': !contact.source
                              }"
                              x-text="contact.source || 'Unknown'"></span>
                        <div class="flex space-x-2">
                            <button @click.stop="editContact(contact)" class="text-blue-600 hover:text-blue-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button @click.stop="deleteContact(contact)" class="text-red-600 hover:text-red-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Contacts List View -->
        <div x-show="!loading && viewMode === 'list'" class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="contact in filteredContacts" :key="contact.id">
                        <tr class="hover:bg-gray-50 cursor-pointer" @click="viewContact(contact)">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-sm" x-text="getInitials(contact.first_name, contact.last_name)"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="`${contact.first_name} ${contact.last_name}`"></div>
                                        <div class="text-sm text-gray-500" x-text="contact.title || 'No title'"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="contact.company || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="contact.email"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="contact.phone || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                      :class="{
                                          'bg-green-100 text-green-800': contact.source === 'website',
                                          'bg-blue-100 text-blue-800': contact.source === 'referral',
                                          'bg-purple-100 text-purple-800': contact.source === 'social',
                                          'bg-orange-100 text-orange-800': contact.source === 'email',
                                          'bg-gray-100 text-gray-800': !contact.source
                                      }"
                                      x-text="contact.source || 'Unknown'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click.stop="editContact(contact)" class="text-blue-600 hover:text-blue-900">Edit</button>
                                    <button @click.stop="deleteContact(contact)" class="text-red-600 hover:text-red-900">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && filteredContacts.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No contacts found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding your first contact.</p>
            <div class="mt-6">
                <button @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 relative z-10" style="pointer-events: auto; cursor: pointer;">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Contact
                </button>
            </div>
        </div>
    </main>

    <script>
        function contactsPage() {
            return {
                loading: true,
                contacts: [],
                filteredContacts: [],
                searchQuery: '',
                viewMode: 'grid',
                sortBy: 'name',
                filters: {
                    source: ''
                },
                loading: {
                    contact: false,
                    lead: false,
                    activity: false
                },
                modalLoading: {
                    contact: false,
                    lead: false,
                    activity: false
                },
                loading: {
                    editContact: false
                },
                currentContact: null,
                currentLead: null,
                editForms: {
                    contact: {
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone: '',
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
                        description: '',
                        type: 'call',
                        status: 'pending',
                        priority: 'medium',
                        due_date: '',
                        contact_id: '',
                        lead_id: ''
                    }
                },
                modals: {
                    contact: false,
                    lead: false,
                    activity: false,
                    viewContact: false,
                    editContact: false,
                    deleteContact: false
                },
                contactToDelete: null,
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
                    console.log('🚀 Initializing Contacts Page...');
                    await this.loadContacts();
                    this.applyFilters();
                    console.log('✅ Contacts page initialized');
                },

                async loadContacts() {
                    try {
                        console.log('📋 Loading contacts...');
                        const response = await fetch('/api/contacts');
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.contacts = data.contacts || this.generateMockContacts();
                        } else {
                            // Fallback to mock data
                            this.contacts = this.generateMockContacts();
                        }
                        
                        console.log('📋 Contacts loaded:', this.contacts.length);
                    } catch (error) {
                        console.error('❌ Failed to load contacts:', error);
                        // Use mock data as fallback
                        this.contacts = this.generateMockContacts();
                    } finally {
                        this.loading = false;
                    }
                },

                generateMockContacts() {
                    return [
                        { id: 1, first_name: 'John', last_name: 'Doe', email: 'john.doe@example.com', phone: '+1-555-0123', title: 'CEO', company: 'TechCorp Inc.', source: 'website', created_at: '2024-01-15' },
                        { id: 2, first_name: 'Jane', last_name: 'Smith', email: 'jane.smith@startup.com', phone: '+1-555-0124', title: 'CTO', company: 'StartupXYZ', source: 'referral', created_at: '2024-01-14' },
                        { id: 3, first_name: 'Mike', last_name: 'Johnson', email: 'mike.j@bigbusiness.com', phone: '+1-555-0125', title: 'Director', company: 'BigBusiness Ltd.', source: 'social', created_at: '2024-01-13' },
                        { id: 4, first_name: 'Sarah', last_name: 'Wilson', email: 'sarah.w@oldschool.com', phone: '+1-555-0126', title: 'Manager', company: 'OldSchool Corp', source: 'email', created_at: '2024-01-12' },
                        { id: 5, first_name: 'David', last_name: 'Brown', email: 'david.brown@securebank.com', phone: '+1-555-0127', title: 'VP Security', company: 'SecureBank', source: 'website', created_at: '2024-01-11' }
                    ];
                },

                searchContacts() {
                    this.applyFilters();
                },

                applyFilters() {
                    let filtered = [...this.contacts];

                    // Apply search filter
                    if (this.searchQuery.trim()) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(contact => 
                            contact.first_name.toLowerCase().includes(query) ||
                            contact.last_name.toLowerCase().includes(query) ||
                            contact.email.toLowerCase().includes(query) ||
                            (contact.company && contact.company.toLowerCase().includes(query)) ||
                            (contact.title && contact.title.toLowerCase().includes(query))
                        );
                    }

                    // Apply source filter
                    if (this.filters.source) {
                        filtered = filtered.filter(contact => contact.source === this.filters.source);
                    }

                    this.filteredContacts = filtered;
                    this.sortContacts();
                },

                sortContacts() {
                    this.filteredContacts.sort((a, b) => {
                        switch (this.sortBy) {
                            case 'name':
                                return `${a.first_name} ${a.last_name}`.localeCompare(`${b.first_name} ${b.last_name}`);
                            case 'company':
                                return (a.company || '').localeCompare(b.company || '');
                            case 'created_at':
                                return new Date(b.created_at) - new Date(a.created_at);
                            case 'last_contact':
                                return new Date(b.last_contact || b.created_at) - new Date(a.last_contact || a.created_at);
                            default:
                                return 0;
                        }
                    });
                },

                toggleView() {
                    this.viewMode = this.viewMode === 'grid' ? 'list' : 'grid';
                },

                getInitials(firstName, lastName) {
                    return `${firstName?.charAt(0) || ''}${lastName?.charAt(0) || ''}`.toUpperCase();
                },

                viewContact(contact) {
                    this.currentContact = contact;
                    this.modals.viewContact = true;
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

                editContact(contact) {
                    this.currentContact = contact;
                    // Populate edit form with current contact data
                    this.editForms.contact = {
                        first_name: contact.first_name,
                        last_name: contact.last_name,
                        email: contact.email,
                        phone: contact.phone || '',
                        company: contact.company || '',
                        source: contact.source || 'website',
                        notes: contact.notes || ''
                    };
                    this.closeModal('viewContact');
                    this.modals.editContact = true;
                },

                deleteContact(contact) {
                    this.contactToDelete = contact;
                    this.modals.deleteContact = true;
                },

                confirmDeleteContact() {
                    if (this.contactToDelete) {
                        console.log('Deleting contact:', this.contactToDelete);
                        // Here you could make an API call to delete
                        this.contacts = this.contacts.filter(c => c.id !== this.contactToDelete.id);
                        this.applyFilters();
                        this.contactToDelete = null;
                        this.modals.deleteContact = false;
                    }
                },

                closeModal(type) {
                    this.modals[type] = false;
                    // Reset form
                    if (this.forms[type]) {
                        Object.keys(this.forms[type]).forEach(key => {
                            if (typeof this.forms[type][key] === 'string') {
                                this.forms[type][key] = key === 'source' ? 'website' : 
                                                       key === 'status' ? 'new' : 
                                                       key === 'priority' ? 'medium' : 
                                                       key === 'type' ? 'call' : '';
                            }
                        });
                    }
                },

                async submitForm(type) {
                    this.modalLoading[type] = true;
                    
                    try {
                        console.log(`Submitting ${type} form:`, this.forms[type]);
                        
                        // Simulate API call
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                        // Add to local data
                        if (type === 'contact') {
                            const newContact = {
                                id: Date.now(),
                                ...this.forms.contact,
                                created_at: new Date().toISOString().split('T')[0]
                            };
                            this.contacts.unshift(newContact);
                            this.applyFilters();
                        }
                        
                        this.closeModal(type);
                        console.log(`✅ ${type} created successfully`);
                        if (type === 'contact') {
                            this.showNotification('Contact created successfully!', 'success');
                        }
                        
                    } catch (error) {
                        console.error(`❌ Failed to create ${type}:`, error);
                    } finally {
                        this.modalLoading[type] = false;
                    }
                },

                openAddModal() {
                    this.modals.contact = true;
                },

                async updateContact() {
                    this.loading.editContact = true;
                    
                    try {
                        // Simulate API call
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                        // Update the contact in the array
                        const contactIndex = this.contacts.findIndex(c => c.id === this.currentContact.id);
                        if (contactIndex !== -1) {
                            this.contacts[contactIndex] = {
                                ...this.contacts[contactIndex],
                                ...this.editForms.contact
                            };
                        }
                        
                        // Refresh the display
                        this.applyFilters();
                        
                        // Close modal and show success
                        this.closeModal('editContact');
                        this.showNotification('Contact updated successfully!', 'success');
                        
                    } catch (error) {
                        console.error('Error updating contact:', error);
                        this.showNotification('Error updating contact. Please try again.', 'error');
                    } finally {
                        this.loading.editContact = false;
                    }
                }
            }
        }
    </script>

    @include('partials.modals')
</body>
</html>