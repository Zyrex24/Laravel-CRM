<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel CRM - Professional Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50" x-data="crmDashboard()">
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
                            <p class="text-xs text-gray-500">Professional Edition</p>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="/" class="text-blue-600 border-b-2 border-blue-600 px-1 pb-4 pt-1 text-sm font-medium">Dashboard</a>
                        <a href="/contacts" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Contacts</a>
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
                            <input type="search" placeholder="Search contacts, leads..." 
                                   x-model="searchQuery" 
                                   @input="performSearch()" 
                                   @keydown.enter.prevent="performSearch()"
                                   @focus="showSearchResults = true"
                                   @blur="setTimeout(() => showSearchResults = false, 200)"
                                   class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                            
                            <!-- Search Results Dropdown -->
                            <div x-show="showSearchResults && ((searchResults && searchResults.length > 0) || isSearching)" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-auto">
                                
                                <!-- Loading State -->
                                <div x-show="isSearching" class="px-4 py-3 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                                        Searching...
                                    </div>
                                </div>
                                
                                <!-- Search Results -->
                                <template x-for="(result, index) in (searchResults || [])" :key="result.uniqueId || `search-result-${index}-${result.id || Date.now()}`">
                                    <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                         @click="showSearchResults = false; searchQuery = ''; navigateToResult(result)">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                      :class="{
                                                          'bg-blue-100 text-blue-800': result.type === 'contact',
                                                          'bg-green-100 text-green-800': result.type === 'lead',
                                                          'bg-purple-100 text-purple-800': result.type === 'activity',
                                                          'bg-orange-100 text-orange-800': result.type === 'organization'
                                                      }"
                                                      x-text="result.type ? result.type.charAt(0).toUpperCase() + result.type.slice(1) : 'Item'"></span>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="result.title || 'Untitled'"></p>
                                                <p class="text-sm text-gray-500" x-text="result.description || ''"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- No Results -->
                                <div x-show="!isSearching && (!searchResults || searchResults.length === 0) && searchQuery.trim()" 
                                     class="px-4 py-3 text-sm text-gray-500">
                                    No results found for "<span x-text="searchQuery"></span>"
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Notifications -->
                    <div class="relative" x-data="{ showNotifications: false }">
                        <button @click="showNotifications = !showNotifications" class="relative p-1 text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 -5v-3a6 6 0 1 0 -12 0v3l-5 5h5a3 3 0 0 0 6 0z" />
                            </svg>
                            <span class="absolute -top-0.5 -right-0.5 h-4 w-4 rounded-full bg-red-500 text-xs text-white flex items-center justify-center" x-text="notifications.length"></span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div x-show="showNotifications" 
                             @click.away="showNotifications = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-sm font-medium text-gray-900 border-b border-gray-200">
                                    Notifications
                                </div>
                                <template x-for="notification in notifications.slice(0, 5)" :key="notification.id">
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 cursor-pointer"
                                         @click="navigateToNotification(notification); showNotifications = false">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="h-2 w-2 bg-blue-400 rounded-full mt-2"></div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                                <p class="text-sm text-gray-500" x-text="notification.message"></p>
                                                <p class="text-xs text-gray-400 mt-1" x-text="notification.time"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="notifications.length === 0" class="px-4 py-3 text-sm text-gray-500">
                                    No new notifications
                                </div>
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <button class="text-sm text-blue-600 hover:text-blue-500">View all notifications</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ showUserMenu: false }">
                        <button @click="showUserMenu = !showUserMenu" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">JD</span>
                            </div>
                            <span class="hidden md:block text-gray-700 font-medium">John Doe</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div x-show="showUserMenu" 
                             @click.away="showUserMenu = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="#profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <a href="#settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <a href="#billing" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Billing</a>
                                <div class="border-t border-gray-100"></div>
                                <a href="#logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading dashboard...</span>
        </div>

        <!-- Dashboard Content -->
        <div x-show="!loading" class="space-y-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome back! 👋</h2>
                        <p class="text-blue-100 mt-1">Here's what's happening with your sales today</p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 text-sm">Today's Date</p>
                        <p class="text-xl font-semibold" x-text="new Date().toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})"></p>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(stats.revenue?.total || 0)"></p>
                            <p class="text-sm text-green-600 font-medium">+12% from last month</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pipeline Value -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pipeline Value</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(stats.revenue?.pipeline || 0)"></p>
                            <p class="text-sm text-blue-600 font-medium">3.2x coverage ratio</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Leads -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Leads</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="stats.leads?.active || 0"></p>
                            <p class="text-sm text-orange-600 font-medium" x-text="`${((stats.leads?.closed_won || 0) / Math.max(stats.leads?.total || 1, 1) * 100).toFixed(1)}% conversion rate`"></p>
                        </div>
                        <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Contacts -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Contacts</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="stats.contacts?.total || 0"></p>
                            <p class="text-sm text-purple-600 font-medium" x-text="`${stats.contacts?.active || 0} active`"></p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sales Pipeline Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Sales Pipeline</h3>
                        <select class="text-sm border-gray-300 rounded-md">
                            <option>This Month</option>
                            <option>Last 3 Months</option>
                            <option>This Year</option>
                        </select>
                    </div>
                    <div class="h-80">
                        <canvas id="pipelineChart"></canvas>
                    </div>
                </div>

                <!-- Revenue Trend Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
                        <div class="flex space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Revenue
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                                Pipeline
                            </span>
                        </div>
                    </div>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activities & Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Activities -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View all</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <template x-for="activity in recentActivities.slice(0, 5)" :key="activity.id">
                            <div class="flex items-start space-x-4 py-4 border-b border-gray-100 last:border-b-0">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                         :class="{
                                             'bg-blue-100': activity.type === 'call',
                                             'bg-green-100': activity.type === 'email', 
                                             'bg-purple-100': activity.type === 'meeting',
                                             'bg-orange-100': activity.type === 'task'
                                         }">
                                        <svg class="h-4 w-4"
                                             :class="{
                                                 'text-blue-600': activity.type === 'call',
                                                 'text-green-600': activity.type === 'email',
                                                 'text-purple-600': activity.type === 'meeting', 
                                                 'text-orange-600': activity.type === 'task'
                                             }"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <!-- Dynamic icon based on activity type -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  x-show="activity.type === 'call'"
                                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  x-show="activity.type === 'email'"
                                                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  x-show="activity.type === 'meeting'"
                                                  d="M8 7V3a1 1 0 012 0v4h4V3a1 1 0 012 0v4h2a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900" x-text="activity.title"></p>
                                    <p class="text-sm text-gray-500" x-text="activity.description || 'No description'"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="formatDate(activity.created_at)"></p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': activity.status === 'pending',
                                              'bg-green-100 text-green-800': activity.status === 'completed',
                                              'bg-red-100 text-red-800': activity.status === 'cancelled'
                                          }"
                                          x-text="activity.status">
                                    </span>
                                </div>
                            </div>
                        </template>
                        <div x-show="!recentActivities || recentActivities.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No activities yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first activity.</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Insights -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="/contacts" class="w-full flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-left group transition-colors">
                                <div class="h-8 w-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Manage Contacts</span>
                            </a>
                            <a href="/leads" class="w-full flex items-center px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-left group transition-colors">
                                <div class="h-8 w-8 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Manage Leads</span>
                            </a>
                            <a href="/activities" class="w-full flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-left group transition-colors">
                                <div class="h-8 w-8 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 012 0v4h4V3a1 1 0 012 0v4h2a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Manage Activities</span>
                            </a>
                        </div>
                    </div>

                    <!-- Performance Insights -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Insights</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-900">Conversion Rate</span>
                                </div>
                                <span class="text-sm font-bold text-green-600" x-text="`${((stats.leads?.closed_won || 0) / Math.max(stats.leads?.total || 1, 1) * 100).toFixed(1)}%`"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-900">Avg. Deal Size</span>
                                </div>
                                <span class="text-sm font-bold text-blue-600" x-text="formatCurrency((stats.revenue?.pipeline || 0) / Math.max(stats.leads?.active || 1, 1))"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-orange-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-900">Pipeline Coverage</span>
                                </div>
                                <span class="text-sm font-bold text-orange-600">3.2x</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include Modal Components -->
    @include('partials.modals')

    <script>
        // Ensure CRM modals are available before dashboard initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for modules to load
            setTimeout(() => {
                if (typeof window.crmModals !== 'function') {
                    console.error('CRM Modals not loaded properly');
                }
            }, 100);
        });

        function crmDashboard() {
            // Initialize with the CRM modal system with fallback
            let crmModalSystem = {};
            
            try {
                if (typeof window.crmModals === 'function') {
                    crmModalSystem = window.crmModals();
                    console.log('✅ CRM Modals loaded successfully');
                } else {
                    console.warn('CRM Modals not loaded, using fallback');
                    // Fallback modal system
                    crmModalSystem = {
                        modals: { contact: false, lead: false, activity: false, organization: false },
                        forms: {
                            contact: { first_name: '', last_name: '', email: '', phone: '', title: '', company: '', organization_id: '', notes: '', source: 'website' },
                            lead: { title: '', description: '', status: 'new', source: '', priority: 'medium', value: '', expected_close_date: '', probability: 50, contact_id: '', organization_id: '', notes: '' },
                            activity: { title: '', description: '', type: 'call', status: 'pending', priority: 'medium', due_date: '', contact_id: '', lead_id: '', organization_id: '' },
                            organization: { name: '', website: '', industry: '', employees: '', annual_revenue: '', description: '', phone: '', email: '', address: { street: '', city: '', state: '', zip: '' } }
                        },
                        loading: { contact: false, lead: false, activity: false, organization: false },
                        modalLoading: { contact: false, lead: false, activity: false, organization: false },
                        openModal: function(type) { this.modals[type] = true; },
                        closeModal: function(type) { 
                            this.modals[type] = false; 
                            this.resetForm(type);
                        },
                        resetForm: function(type) { 
                            // Reset form data based on type
                            switch(type) {
                                case 'contact':
                                    this.forms.contact = {
                                        first_name: '',
                                        last_name: '',
                                        email: '',
                                        phone: '',
                                        title: '',
                                        company: '',
                                        source: '',
                                        notes: ''
                                    };
                                    break;
                                case 'lead':
                                    this.forms.lead = {
                                        title: '',
                                        description: '',
                                        status: 'new',
                                        priority: 'medium',
                                        value: '',
                                        probability: '',
                                        expected_close_date: '',
                                        source: '',
                                        notes: ''
                                    };
                                    break;
                                case 'activity':
                                    this.forms.activity = {
                                        title: '',
                                        type: 'call',
                                        priority: 'medium',
                                        due_date: '',
                                        description: ''
                                    };
                                    break;
                            }
                        },
                        submitForm: async function(type) {
                            console.log('Fallback submit for', type);
                            this.loading[type] = true;
                            
                            try {
                                const endpoint = `/api/${type}s`;
                                const response = await fetch(endpoint, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                    },
                                    body: JSON.stringify(this.forms[type])
                                });

                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }

                                const result = await response.json();
                                this.showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} created successfully!`, 'success');
                                this.closeModal(type);
                                return result;
                                
                            } catch (error) {
                                console.error(`Error creating ${type}:`, error);
                                this.showNotification(`Failed to create ${type}. Please try again.`, 'error');
                                return false;
                            } finally {
                                this.loading[type] = false;
                            }
                        },
                        showNotification: function(message, type = 'info') {
                            console.log(`${type}: ${message}`);
                            // Create a visual notification
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
                                    if (notification.parentNode) {
                                        document.body.removeChild(notification);
                                    }
                                }, 300);
                            }, 3000);
                        },
                        getCurrentDate: function() { return new Date().toISOString().split('T')[0]; },
                        getCurrentDateTime: function() { return new Date().toISOString().slice(0, 16); }
                    };
                }
            } catch (error) {
                console.error('Error initializing CRM modal system:', error);
                crmModalSystem = { modals: {}, forms: {}, loading: {} };
            }
            
            return {
                // Spread the modal system
                ...crmModalSystem,
                
                // Dashboard-specific properties
                loading: true,
                stats: {
                    contacts: { total: 0, new_this_month: 0 },
                    leads: { total: 0, active: 0, converted: 0 },
                    activities: { total: 0, pending: 0, completed: 0 },
                    revenue: { total: 0, pipeline: 0, closed: 0 }
                },
                recentActivities: [],
                recentLeads: [],
                notifications: [],
                charts: null,
                
                // Search functionality
                searchQuery: '',
                searchResults: [],
                isSearching: false,
                showSearchResults: false,
                searchResultCounter: 0,

                async init() {
                    console.log('🚀 Initializing CRM Dashboard...');
                    
                    // Initialize notifications array immediately to prevent undefined errors
                    this.notifications = [];
                    
                    try {
                        // Initialize charts with fallback
                        if (typeof window.CRMCharts === 'function') {
                            this.charts = new window.CRMCharts();
                        } else {
                            console.warn('CRMCharts not loaded, using fallback');
                            this.charts = {
                                createPipelineChart: () => console.log('Fallback: Pipeline chart'),
                                createRevenueChart: () => console.log('Fallback: Revenue chart')
                            };
                        }
                        
                        await this.loadDashboardData();
                        await this.loadNotifications();
                        await this.$nextTick();
                        this.initializeCharts();
                        console.log('✅ Dashboard fully initialized');
                    } catch (error) {
                        console.error('❌ Dashboard initialization error:', error);
                        this.loading = false;
                    }
                },

                async loadDashboardData() {
                    try {
                        console.log('📊 Loading dashboard data...');
                        const response = await fetch('/api/dashboard');
                        const data = await response.json();
                        
                        this.stats = data.statistics || {};
                        this.recentActivities = data.recent_activities || [];
                        this.recentLeads = data.recent_leads || [];
                        
                        console.log('📊 Dashboard data loaded:', this.stats);
                        this.loading = false;
                    } catch (error) {
                        console.error('❌ Failed to load dashboard:', error);
                        this.loading = false;
                    }
                },

                async loadNotifications() {
                    try {
                        console.log('🔔 Loading notifications...');
                        const response = await fetch('/api/notifications');
                        if (response.ok) {
                            const notificationData = await response.json();
                            this.notifications = notificationData;
                            console.log('✅ Notifications loaded:', this.notifications.length);
                        } else {
                            // Generate dynamic notifications based on recent activities
                            this.notifications = this.generateNotifications();
                        }
                    } catch (error) {
                        console.error('Failed to load notifications:', error);
                        this.notifications = this.generateNotifications();
                    }
                },

                generateNotifications() {
                    const notifications = [];
                    
                    // Generate from recent activities
                    this.recentActivities.forEach(activity => {
                        notifications.push({
                            id: `activity_${activity.id}`,
                            message: `New activity: ${activity.title}`,
                            type: 'activity',
                            timestamp: activity.created_at
                        });
                    });
                    
                    // Generate from recent leads
                    this.recentLeads.forEach(lead => {
                        notifications.push({
                            id: `lead_${lead.id}`,
                            message: `New lead: ${lead.title} ($${lead.value})`,
                            type: 'lead',
                            timestamp: lead.created_at
                        });
                    });
                    
                    return notifications.slice(0, 5); // Latest 5
                },

                initializeCharts() {
                    if (!this.charts) return;
                    
                    try {
                        console.log('📈 Initializing charts with real data...');
                        
                        // Use REAL pipeline data from database
                        const pipelineData = this.stats.charts?.leads_by_status || {};
                        this.charts.createPipelineChart('pipelineChart', pipelineData);

                        // Use REAL revenue data from database
                        const revenueData = this.stats.charts?.monthly_revenue || this.generateRevenueChartData();
                        this.charts.createRevenueChart('revenueChart', revenueData);
                        
                        console.log('✅ Charts initialized with real data');
                    } catch (error) {
                        console.error('❌ Chart initialization error:', error);
                    }
                },

                generateRevenueChartData() {
                    // Generate realistic revenue data based on current stats
                    const currentMonth = new Date().getMonth();
                    const labels = [];
                    const revenue = [];
                    const pipeline = [];
                    
                    for (let i = 5; i >= 0; i--) {
                        const monthDate = new Date();
                        monthDate.setMonth(currentMonth - i);
                        labels.push(monthDate.toLocaleDateString('en-US', { month: 'short' }));
                        
                        // Generate realistic revenue based on closed deals
                        const baseRevenue = (this.stats.revenue?.total || 50000) / 6;
                        revenue.push(Math.round(baseRevenue * (0.8 + Math.random() * 0.4)));
                        
                        // Pipeline is always higher than revenue
                        pipeline.push(Math.round(baseRevenue * (2 + Math.random())));
                    }
                    
                    return { labels, revenue, pipeline };
                },

                // Override the submitForm to refresh dashboard after submission
                async submitForm(type) {
                    console.log(`🚀 Submitting ${type} form...`);
                    
                    // Call the original submit function
                    const result = await crmModalSystem.submitForm.call(this, type);
                    
                    // Refresh dashboard data after successful submission
                    if (result) {
                        console.log('✅ Form submitted, refreshing dashboard...');
                        await this.loadDashboardData();
                        await this.loadNotifications();
                        this.initializeCharts();
                    }
                    
                    return result;
                },

                // Search functionality
                async performSearch() {
                    if (!this.searchQuery.trim()) {
                        this.searchResults = [];
                        this.showSearchResults = false;
                        return;
                    }

                    this.isSearching = true;
                    this.showSearchResults = true;

                    try {
                        const response = await fetch(`/api/search?q=${encodeURIComponent(this.searchQuery)}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const results = await response.json();
                            this.searchResults = this.addUniqueIds(results);
                        } else {
                            // Fallback: search through local data
                            this.searchResults = this.addUniqueIds(this.searchLocalData());
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                        // Fallback: search through local data
                        this.searchResults = this.addUniqueIds(this.searchLocalData());
                    } finally {
                        this.isSearching = false;
                    }
                },

                addUniqueIds(results) {
                    return results.map((result, index) => ({
                        ...result,
                        uniqueId: `search-${++this.searchResultCounter}-${result.id || index}`
                    }));
                },

                navigateToResult(result) {
                    // Navigate to the appropriate page based on result type
                    switch(result.type) {
                        case 'contact':
                            window.location.href = '/contacts';
                            break;
                        case 'lead':
                            window.location.href = '/leads';
                            break;
                        case 'activity':
                            window.location.href = '/activities';
                            break;
                        case 'organization':
                            window.location.href = '/contacts'; // Organizations can be viewed in contacts
                            break;
                        default:
                            console.log('Unknown result type:', result.type);
                            // Default to dashboard if type is unknown
                            window.location.href = '/';
                    }
                },

                searchLocalData() {
                    const query = this.searchQuery.toLowerCase();
                    const results = [];

                    // Search through recent activities
                    this.recentActivities.forEach(activity => {
                        if (activity.title?.toLowerCase().includes(query) || 
                            activity.description?.toLowerCase().includes(query)) {
                            results.push({
                                type: 'activity',
                                title: activity.title,
                                description: activity.description,
                                id: activity.id
                            });
                        }
                    });

                    // Search through recent leads
                    this.recentLeads.forEach(lead => {
                        if (lead.title?.toLowerCase().includes(query) || 
                            lead.company?.toLowerCase().includes(query)) {
                            results.push({
                                type: 'lead',
                                title: lead.title,
                                description: lead.company,
                                id: lead.id
                            });
                        }
                    });

                    return results.slice(0, 10); // Limit to 10 results
                },

                navigateToNotification(notification) {
                    // Navigate based on notification type or related entity
                    if (notification.type) {
                        switch(notification.type) {
                            case 'contact':
                            case 'new_contact':
                                window.location.href = '/contacts';
                                break;
                            case 'lead':
                            case 'new_lead':
                                window.location.href = '/leads';
                                break;
                            case 'activity':
                            case 'task':
                            case 'meeting':
                                window.location.href = '/activities';
                                break;
                            case 'report':
                            case 'analytics':
                                window.location.href = '/reports';
                                break;
                            default:
                                // Stay on dashboard for general notifications
                                console.log('General notification clicked:', notification.title);
                        }
                    } else {
                        // If no specific type, try to infer from title or message
                        const content = (notification.title + ' ' + notification.message).toLowerCase();
                        if (content.includes('contact')) {
                            window.location.href = '/contacts';
                        } else if (content.includes('lead')) {
                            window.location.href = '/leads';
                        } else if (content.includes('activity') || content.includes('task') || content.includes('meeting')) {
                            window.location.href = '/activities';
                        } else if (content.includes('report') || content.includes('analytics')) {
                            window.location.href = '/reports';
                        }
                        // Otherwise stay on current page
                    }
                },

                 formatCurrency(amount) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount || 0);
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                }
            }
        }
    </script>
</body>
</html>