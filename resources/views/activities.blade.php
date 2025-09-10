<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Activities - Laravel CRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50" x-data="activitiesPage()">
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
                            <p class="text-xs text-gray-500">Activity Management</p>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="/" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Dashboard</a>
                        <a href="/contacts" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Contacts</a>
                        <a href="/leads" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Leads</a>
                        <a href="/activities" class="text-blue-600 border-b-2 border-blue-600 px-1 pb-4 pt-1 text-sm font-medium">Activities</a>
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
                            <input type="search" placeholder="Search activities..." 
                                   x-model="searchQuery"
                                   @input="searchActivities()"
                                   class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <!-- Add Activity Button -->
                    <button @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Schedule Activity
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Activities</h2>
            <p class="text-gray-600 mt-1">Track and manage your sales activities and follow-ups</p>
        </div>

        <!-- Activity Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-red-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Overdue</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getOverdueActivities().length"></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Today</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getTodayActivities().length"></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">This Week</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getThisWeekActivities().length"></p>
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
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900" x-text="getCompletedActivities().length"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Type:</label>
                    <select x-model="filters.type" @change="applyFilters()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All Types</option>
                        <option value="call">Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                        <option value="task">Task</option>
                        <option value="follow_up">Follow Up</option>
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
                    <label class="text-sm font-medium text-gray-700">Status:</label>
                    <select x-model="filters.status" @change="applyFilters()" class="text-sm border-gray-300 rounded-md">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select x-model="sortBy" @change="sortActivities()" class="text-sm border-gray-300 rounded-md">
                        <option value="due_date">Due Date</option>
                        <option value="title">Title</option>
                        <option value="priority">Priority</option>
                        <option value="created_at">Date Created</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
            <span class="ml-3 text-gray-600">Loading activities...</span>
        </div>

        <!-- Activities List -->
        <div x-show="!loading" class="space-y-4">
            <template x-for="activity in filteredActivities" :key="activity.id">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer" @click="viewActivity(activity)">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Activity Type Icon -->
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center" 
                                     :class="{
                                         'bg-blue-100': activity.type === 'call',
                                         'bg-green-100': activity.type === 'email',
                                         'bg-purple-100': activity.type === 'meeting',
                                         'bg-yellow-100': activity.type === 'task',
                                         'bg-orange-100': activity.type === 'follow_up'
                                     }">
                                    <!-- Call Icon -->
                                    <svg x-show="activity.type === 'call'" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <!-- Email Icon -->
                                    <svg x-show="activity.type === 'email'" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <!-- Meeting Icon -->
                                    <svg x-show="activity.type === 'meeting'" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <!-- Task Icon -->
                                    <svg x-show="activity.type === 'task'" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <!-- Follow Up Icon -->
                                    <svg x-show="activity.type === 'follow_up'" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Activity Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h3 class="text-lg font-medium text-gray-900" x-text="activity.title"></h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                          :class="{
                                              'bg-red-100 text-red-800': activity.priority === 'high',
                                              'bg-yellow-100 text-yellow-800': activity.priority === 'medium',
                                              'bg-green-100 text-green-800': activity.priority === 'low'
                                          }"
                                          x-text="activity.priority"></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2" x-text="activity.description"></p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span x-text="formatDateTime(activity.due_date)"></span>
                                    </div>
                                    <div class="flex items-center" x-show="activity.contact">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span x-text="activity.contact"></span>
                                    </div>
                                    <div class="flex items-center" x-show="activity.lead">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span x-text="activity.lead"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status and Actions -->
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                  :class="{
                                      'bg-yellow-100 text-yellow-800': activity.status === 'pending',
                                      'bg-green-100 text-green-800': activity.status === 'completed',
                                      'bg-red-100 text-red-800': activity.status === 'cancelled'
                                  }"
                                  x-text="activity.status"></span>
                            <div class="flex space-x-2">
                                <button @click.stop="markComplete(activity)" 
                                        x-show="activity.status === 'pending'"
                                        class="text-green-600 hover:text-green-800">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                <button @click.stop="editActivity(activity)" class="text-blue-600 hover:text-blue-800">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click.stop="deleteActivity(activity)" class="text-red-600 hover:text-red-800">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && filteredActivities.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No activities found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by scheduling your first activity.</p>
            <div class="mt-6">
                <button @click="openAddModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Schedule Activity
                </button>
            </div>
        </div>
    </main>

    <script>
        function activitiesPage() {
            return {
                loading: true,
                activities: [],
                filteredActivities: [],
                searchQuery: '',
                sortBy: 'due_date',
                filters: {
                    type: '',
                    priority: '',
                    status: ''
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
                    viewActivity: false,
                    editActivity: false,
                },
                currentActivity: null,
                currentContact: null,
                currentLead: null,
                contactToDelete: null,
                editForms: {
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
                        description: '',
                        type: 'call',
                        status: 'pending',
                        priority: 'medium',
                        due_date: '',
                        contact_id: '',
                        lead_id: ''
                    }
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
                    console.log('🚀 Initializing Activities Page...');
                    await this.loadActivities();
                    this.applyFilters();
                    console.log('✅ Activities page initialized');
                },

                async loadActivities() {
                    try {
                        console.log('📋 Loading activities...');
                        const response = await fetch('/api/activities');
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.activities = data.activities || this.generateMockActivities();
                        } else {
                            // Fallback to mock data
                            this.activities = this.generateMockActivities();
                        }
                        
                        console.log('📋 Activities loaded:', this.activities.length);
                    } catch (error) {
                        console.error('❌ Failed to load activities:', error);
                        // Use mock data as fallback
                        this.activities = this.generateMockActivities();
                    } finally {
                        this.loading = false;
                    }
                },

                generateMockActivities() {
                    const now = new Date();
                    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    const tomorrow = new Date(today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    const nextWeek = new Date(today);
                    nextWeek.setDate(nextWeek.getDate() + 7);
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);

                    return [
                        { id: 1, title: 'Follow up call with John Doe', description: 'Discuss enterprise software requirements', type: 'call', priority: 'high', status: 'pending', due_date: yesterday.toISOString(), contact: 'John Doe', lead: null, created_at: '2024-01-15' },
                        { id: 2, title: 'Send proposal to StartupXYZ', description: 'Email detailed proposal for CRM implementation', type: 'email', priority: 'high', status: 'pending', due_date: today.toISOString(), contact: 'Jane Smith', lead: 'Small Business Package', created_at: '2024-01-14' },
                        { id: 3, title: 'Demo meeting with BigBusiness', description: 'Product demonstration and Q&A session', type: 'meeting', priority: 'medium', status: 'pending', due_date: tomorrow.toISOString(), contact: 'Mike Johnson', lead: 'Enterprise Software Deal', created_at: '2024-01-13' },
                        { id: 4, title: 'Prepare contract documents', description: 'Draft and review legal documents for SecureBank deal', type: 'task', priority: 'medium', status: 'completed', due_date: yesterday.toISOString(), contact: 'David Brown', lead: null, created_at: '2024-01-12' },
                        { id: 5, title: 'Weekly check-in with OldSchool Corp', description: 'Regular follow-up to maintain relationship', type: 'follow_up', priority: 'low', status: 'pending', due_date: nextWeek.toISOString(), contact: 'Sarah Wilson', lead: null, created_at: '2024-01-11' }
                    ];
                },

                searchActivities() {
                    this.applyFilters();
                },

                applyFilters() {
                    let filtered = [...this.activities];

                    // Apply search filter
                    if (this.searchQuery.trim()) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(activity => 
                            activity.title.toLowerCase().includes(query) ||
                            activity.description.toLowerCase().includes(query) ||
                            (activity.contact && activity.contact.toLowerCase().includes(query)) ||
                            (activity.lead && activity.lead.toLowerCase().includes(query))
                        );
                    }

                    // Apply type filter
                    if (this.filters.type) {
                        filtered = filtered.filter(activity => activity.type === this.filters.type);
                    }

                    // Apply priority filter
                    if (this.filters.priority) {
                        filtered = filtered.filter(activity => activity.priority === this.filters.priority);
                    }

                    // Apply status filter
                    if (this.filters.status) {
                        filtered = filtered.filter(activity => activity.status === this.filters.status);
                    }

                    this.filteredActivities = filtered;
                    this.sortActivities();
                },

                sortActivities() {
                    this.filteredActivities.sort((a, b) => {
                        switch (this.sortBy) {
                            case 'due_date':
                                return new Date(a.due_date) - new Date(b.due_date);
                            case 'title':
                                return a.title.localeCompare(b.title);
                            case 'priority':
                                const priorityOrder = { high: 3, medium: 2, low: 1 };
                                return priorityOrder[b.priority] - priorityOrder[a.priority];
                            case 'created_at':
                                return new Date(b.created_at) - new Date(a.created_at);
                            default:
                                return 0;
                        }
                    });
                },

                getOverdueActivities() {
                    const now = new Date();
                    return this.activities.filter(activity => 
                        activity.status === 'pending' && new Date(activity.due_date) < now
                    );
                },

                getTodayActivities() {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const tomorrow = new Date(today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    
                    return this.activities.filter(activity => {
                        const activityDate = new Date(activity.due_date);
                        return activityDate >= today && activityDate < tomorrow;
                    });
                },

                getThisWeekActivities() {
                    const now = new Date();
                    const startOfWeek = new Date(now);
                    startOfWeek.setDate(now.getDate() - now.getDay());
                    startOfWeek.setHours(0, 0, 0, 0);
                    const endOfWeek = new Date(startOfWeek);
                    endOfWeek.setDate(startOfWeek.getDate() + 7);
                    
                    return this.activities.filter(activity => {
                        const activityDate = new Date(activity.due_date);
                        return activityDate >= startOfWeek && activityDate < endOfWeek;
                    });
                },

                getCompletedActivities() {
                    return this.activities.filter(activity => activity.status === 'completed');
                },

                formatDateTime(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
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

                viewActivity(activity) {
                    console.log('Viewing activity:', activity);
                    this.currentActivity = activity;
                    this.modals.viewActivity = true;
                },

                editActivity(activity) {
                    console.log('Editing activity:', activity);
                    this.currentActivity = activity;
                    // Populate edit form with current activity data
                    this.editForms.activity = {
                        title: activity.title,
                        description: activity.description || '',
                        type: activity.type || 'call',
                        status: activity.status || 'pending',
                        priority: activity.priority || 'medium',
                        due_date: activity.due_date || '',
                        contact_id: activity.contact_id || '',
                        lead_id: activity.lead_id || ''
                    };
                    this.modals.editActivity = true;
                },

                async updateActivity() {
                    this.modalLoading.activity = true;
                    try {
                        console.log('Updating activity:', this.currentActivity.id, this.editForms.activity);
                        
                        // Simulate API call delay
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                        // Update the activity in the array
                        const index = this.activities.findIndex(a => a.id === this.currentActivity.id);
                        if (index !== -1) {
                            this.activities[index] = {
                                ...this.activities[index],
                                ...this.editForms.activity
                            };
                        }
                        
                        // Refresh the filtered activities
                        this.applyFilters();
                        
                        // Close modal and show success
                        this.closeModal('editActivity');
                        this.showNotification('Activity updated successfully!', 'success');
                        
                    } catch (error) {
                        console.error('Error updating activity:', error);
                        this.showNotification('Error updating activity. Please try again.', 'error');
                    } finally {
                        this.modalLoading.activity = false;
                    }
                },

                markComplete(activity) {
                    console.log('Marking activity as complete:', activity);
                    activity.status = 'completed';
                    this.applyFilters();
                },

                deleteActivity(activity) {
                    if (confirm(`Are you sure you want to delete ${activity.title}?`)) {
                        console.log('Deleting activity:', activity);
                        this.activities = this.activities.filter(a => a.id !== activity.id);
                        this.applyFilters();
                    }
                },

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
                        console.log(`Submitting ${type} form:`, this.forms[type]);
                        
                        // Simulate API call
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                        // Add to local data
                        if (type === 'activity') {
                            const newActivity = {
                                id: Date.now(),
                                ...this.forms.activity,
                                status: 'pending',
                                created_at: new Date().toISOString()
                            };
                            this.activities.unshift(newActivity);
                            this.applyFilters();
                        }
                        
                        this.closeModal(type);
                        console.log(`✅ ${type} created successfully`);
                        this.showNotification('Activity scheduled successfully!', 'success');
                        
                    } catch (error) {
                        console.error(`❌ Failed to create ${type}:`, error);
                    } finally {
                        this.modalLoading[type] = false;
                    }
                },

                openAddModal() {
                    console.log('Opening add activity modal');
                    this.modals.activity = true;
                }
            }
        }
    </script>

    @include('partials.modals')
</body>
</html>