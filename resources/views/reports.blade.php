<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports - Laravel CRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="h-full bg-gray-50" x-data="reportsPage()">
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
                            <p class="text-xs text-gray-500">Reports & Analytics</p>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="/" class="text-gray-500 hover:text-gray-700 px-1 pb-4 pt-1 text-sm font-medium">Dashboard</a>
                        <a href="#reports" class="text-blue-600 border-b-2 border-blue-600 px-1 pb-4 pt-1 text-sm font-medium">Reports</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Date Range Selector -->
                    <div class="flex items-center space-x-2">
                        <select x-model="selectedPeriod" @change="loadReports()" class="text-sm border-gray-300 rounded-md">
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="365">Last year</option>
                        </select>
                    </div>
                    <!-- Export Button -->
                    <button @click="exportReport()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading reports...</span>
        </div>

        <!-- Reports Content -->
        <div x-show="!loading" class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Sales Reports & Analytics</h2>
                        <p class="text-gray-600 mt-1">Comprehensive insights into your CRM performance</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Report Period</p>
                        <p class="text-lg font-semibold text-gray-900" x-text="getPeriodLabel()"></p>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(metrics.totalRevenue)"></p>
                            <p class="text-sm font-medium" :class="metrics.revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600'" x-text="`${metrics.revenueGrowth >= 0 ? '+' : ''}${metrics.revenueGrowth}% vs previous period`"></p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Conversion Rate -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="`${metrics.conversionRate}%`"></p>
                            <p class="text-sm font-medium" :class="metrics.conversionGrowth >= 0 ? 'text-green-600' : 'text-red-600'" x-text="`${metrics.conversionGrowth >= 0 ? '+' : ''}${metrics.conversionGrowth}% vs previous period`"></p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Deal Size -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Avg Deal Size</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(metrics.avgDealSize)"></p>
                            <p class="text-sm font-medium" :class="metrics.dealSizeGrowth >= 0 ? 'text-green-600' : 'text-red-600'" x-text="`${metrics.dealSizeGrowth >= 0 ? '+' : ''}${metrics.dealSizeGrowth}% vs previous period`"></p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sales Velocity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Sales Velocity</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="`${metrics.salesVelocity} days`"></p>
                            <p class="text-sm font-medium" :class="metrics.velocityGrowth <= 0 ? 'text-green-600' : 'text-red-600'" x-text="`${metrics.velocityGrowth <= 0 ? '' : '+'}${metrics.velocityGrowth} days vs previous period`"></p>
                        </div>
                        <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Revenue Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
                        <div class="flex space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Closed Revenue
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                                Pipeline
                            </span>
                        </div>
                    </div>
                    <div class="h-80">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>

                <!-- Lead Sources -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Lead Sources</h3>
                    </div>
                    <div class="h-80">
                        <canvas id="leadSourcesChart"></canvas>
                    </div>
                </div>

                <!-- Sales Funnel -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Sales Funnel</h3>
                    </div>
                    <div class="h-80">
                        <canvas id="salesFunnelChart"></canvas>
                    </div>
                </div>

                <!-- Activity Performance -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Activity Performance</h3>
                    </div>
                    <div class="h-80">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Performers -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top Performing Leads</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <template x-for="lead in topLeads" :key="lead.id">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900" x-text="lead.title"></p>
                                        <p class="text-sm text-gray-500" x-text="lead.company"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900" x-text="formatCurrency(lead.value)"></p>
                                        <p class="text-xs text-gray-500" x-text="lead.status"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Recent Conversions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Conversions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <template x-for="conversion in recentConversions" :key="conversion.id">
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900" x-text="conversion.title"></p>
                                        <p class="text-sm text-gray-500" x-text="formatDate(conversion.closed_at)"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-green-600" x-text="formatCurrency(conversion.value)"></p>
                                        <p class="text-xs text-green-500">Closed Won</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function reportsPage() {
            return {
                loading: true,
                selectedPeriod: '30',
                metrics: {
                    totalRevenue: 0,
                    revenueGrowth: 0,
                    conversionRate: 0,
                    conversionGrowth: 0,
                    avgDealSize: 0,
                    dealSizeGrowth: 0,
                    salesVelocity: 0,
                    velocityGrowth: 0
                },
                topLeads: [],
                recentConversions: [],
                charts: {},

                async init() {
                    console.log('🚀 Initializing Reports Page...');
                    await this.loadReports();
                    this.initializeCharts();
                    console.log('✅ Reports page initialized');
                },

                async loadReports() {
                    this.loading = true;
                    try {
                        console.log(`📊 Loading reports for ${this.selectedPeriod} days...`);
                        const response = await fetch(`/api/reports?period=${this.selectedPeriod}`);
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.metrics = data.metrics || this.generateMockMetrics();
                            this.topLeads = data.topLeads || this.generateMockLeads();
                            this.recentConversions = data.recentConversions || this.generateMockConversions();
                        } else {
                            // Fallback to mock data
                            this.metrics = this.generateMockMetrics();
                            this.topLeads = this.generateMockLeads();
                            this.recentConversions = this.generateMockConversions();
                        }
                        
                        console.log('📊 Reports data loaded');
                    } catch (error) {
                        console.error('❌ Failed to load reports:', error);
                        // Use mock data as fallback
                        this.metrics = this.generateMockMetrics();
                        this.topLeads = this.generateMockLeads();
                        this.recentConversions = this.generateMockConversions();
                    } finally {
                        this.loading = false;
                        // Refresh charts after data load
                        this.$nextTick(() => {
                            this.initializeCharts();
                        });
                    }
                },

                generateMockMetrics() {
                    return {
                        totalRevenue: 125000 + Math.random() * 50000,
                        revenueGrowth: Math.round((Math.random() - 0.3) * 40),
                        conversionRate: 15 + Math.random() * 10,
                        conversionGrowth: Math.round((Math.random() - 0.4) * 10),
                        avgDealSize: 5000 + Math.random() * 3000,
                        dealSizeGrowth: Math.round((Math.random() - 0.3) * 20),
                        salesVelocity: 30 + Math.random() * 20,
                        velocityGrowth: Math.round((Math.random() - 0.6) * 10)
                    };
                },

                generateMockLeads() {
                    return [
                        { id: 1, title: 'Enterprise Software Deal', company: 'TechCorp Inc.', value: 25000, status: 'Negotiation' },
                        { id: 2, title: 'Marketing Automation', company: 'StartupXYZ', value: 15000, status: 'Proposal' },
                        { id: 3, title: 'CRM Implementation', company: 'BigBusiness Ltd.', value: 35000, status: 'Qualified' },
                        { id: 4, title: 'Cloud Migration', company: 'OldSchool Corp', value: 20000, status: 'Discovery' },
                        { id: 5, title: 'Security Audit', company: 'SecureBank', value: 18000, status: 'Proposal' }
                    ];
                },

                generateMockConversions() {
                    const now = new Date();
                    return [
                        { id: 1, title: 'Website Redesign', value: 12000, closed_at: new Date(now - 2 * 24 * 60 * 60 * 1000) },
                        { id: 2, title: 'Mobile App Development', value: 28000, closed_at: new Date(now - 5 * 24 * 60 * 60 * 1000) },
                        { id: 3, title: 'SEO Optimization', value: 8500, closed_at: new Date(now - 7 * 24 * 60 * 60 * 1000) },
                        { id: 4, title: 'Database Migration', value: 15000, closed_at: new Date(now - 10 * 24 * 60 * 60 * 1000) },
                        { id: 5, title: 'Training Program', value: 6000, closed_at: new Date(now - 12 * 24 * 60 * 60 * 1000) }
                    ];
                },

                initializeCharts() {
                    this.$nextTick(() => {
                        this.createRevenueTrendChart();
                        this.createLeadSourcesChart();
                        this.createSalesFunnelChart();
                        this.createActivityChart();
                    });
                },

                createRevenueTrendChart() {
                    const ctx = document.getElementById('revenueTrendChart');
                    if (!ctx) return;

                    if (this.charts.revenueTrend) {
                        this.charts.revenueTrend.destroy();
                    }

                    const labels = this.generateDateLabels();
                    const revenueData = this.generateRevenueData();
                    const pipelineData = this.generatePipelineData();

                    this.charts.revenueTrend = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Closed Revenue',
                                data: revenueData,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4
                            }, {
                                label: 'Pipeline',
                                data: pipelineData,
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '$' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                },

                createLeadSourcesChart() {
                    const ctx = document.getElementById('leadSourcesChart');
                    if (!ctx) return;

                    if (this.charts.leadSources) {
                        this.charts.leadSources.destroy();
                    }

                    this.charts.leadSources = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Website', 'Referral', 'Social Media', 'Email Campaign', 'Cold Outreach'],
                            datasets: [{
                                data: [35, 25, 15, 15, 10],
                                backgroundColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(34, 197, 94)',
                                    'rgb(168, 85, 247)',
                                    'rgb(249, 115, 22)',
                                    'rgb(239, 68, 68)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                },

                createSalesFunnelChart() {
                    const ctx = document.getElementById('salesFunnelChart');
                    if (!ctx) return;

                    if (this.charts.salesFunnel) {
                        this.charts.salesFunnel.destroy();
                    }

                    this.charts.salesFunnel = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Leads', 'Qualified', 'Proposal', 'Negotiation', 'Closed Won'],
                            datasets: [{
                                label: 'Count',
                                data: [150, 85, 45, 25, 15],
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(34, 197, 94, 0.8)',
                                    'rgba(249, 115, 22, 0.8)',
                                    'rgba(168, 85, 247, 0.8)',
                                    'rgba(34, 197, 94, 0.9)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },

                createActivityChart() {
                    const ctx = document.getElementById('activityChart');
                    if (!ctx) return;

                    if (this.charts.activity) {
                        this.charts.activity.destroy();
                    }

                    this.charts.activity = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Calls', 'Emails', 'Meetings', 'Tasks'],
                            datasets: [{
                                label: 'Completed',
                                data: [45, 78, 23, 56],
                                backgroundColor: 'rgba(34, 197, 94, 0.8)'
                            }, {
                                label: 'Pending',
                                data: [12, 15, 8, 20],
                                backgroundColor: 'rgba(249, 115, 22, 0.8)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },

                generateDateLabels() {
                    const labels = [];
                    const days = parseInt(this.selectedPeriod);
                    const interval = Math.max(1, Math.floor(days / 10));
                    
                    for (let i = days; i >= 0; i -= interval) {
                        const date = new Date();
                        date.setDate(date.getDate() - i);
                        labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
                    }
                    return labels;
                },

                generateRevenueData() {
                    const days = parseInt(this.selectedPeriod);
                    const points = Math.min(10, days);
                    const data = [];
                    let baseValue = 5000;
                    
                    for (let i = 0; i < points; i++) {
                        baseValue += (Math.random() - 0.4) * 2000;
                        data.push(Math.max(0, Math.round(baseValue)));
                    }
                    return data;
                },

                generatePipelineData() {
                    const days = parseInt(this.selectedPeriod);
                    const points = Math.min(10, days);
                    const data = [];
                    let baseValue = 15000;
                    
                    for (let i = 0; i < points; i++) {
                        baseValue += (Math.random() - 0.3) * 3000;
                        data.push(Math.max(0, Math.round(baseValue)));
                    }
                    return data;
                },

                getPeriodLabel() {
                    const periods = {
                        '7': 'Last 7 days',
                        '30': 'Last 30 days',
                        '90': 'Last 90 days',
                        '365': 'Last year'
                    };
                    return periods[this.selectedPeriod] || 'Custom period';
                },

                exportReport() {
                    // Simple CSV export functionality
                    const csvData = [
                        ['Metric', 'Value', 'Growth'],
                        ['Total Revenue', this.formatCurrency(this.metrics.totalRevenue), `${this.metrics.revenueGrowth}%`],
                        ['Conversion Rate', `${this.metrics.conversionRate}%`, `${this.metrics.conversionGrowth}%`],
                        ['Average Deal Size', this.formatCurrency(this.metrics.avgDealSize), `${this.metrics.dealSizeGrowth}%`],
                        ['Sales Velocity', `${this.metrics.salesVelocity} days`, `${this.metrics.velocityGrowth} days`]
                    ];

                    const csvContent = csvData.map(row => row.join(',')).join('\n');
                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `crm-report-${new Date().toISOString().split('T')[0]}.csv`;
                    a.click();
                    window.URL.revokeObjectURL(url);
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