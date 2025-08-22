<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <div class="flex space-x-3">
        <router-link
          to="/contacts/create"
          class="btn btn-secondary"
        >
          Add Contact
        </router-link>
        <router-link
          to="/leads/create"
          class="btn btn-primary"
        >
          Add Lead
        </router-link>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Contacts</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.contacts }}</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Active Leads</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.leads }}</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Open Activities</p>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.activities }}</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Revenue This Month</p>
            <p class="text-2xl font-semibold text-gray-900">${{ stats.revenue.toLocaleString() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Activities -->
      <div class="card">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
          <router-link to="/activities" class="text-sm text-primary-600 hover:text-primary-500">
            View all
          </router-link>
        </div>
        <div class="space-y-3">
          <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="w-2 h-2 bg-green-400 rounded-full"></div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-900 truncate">{{ activity.title }}</p>
              <p class="text-xs text-gray-500">{{ activity.time }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
          <router-link
            to="/contacts/create"
            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <span class="text-sm font-medium text-gray-900">Add Contact</span>
          </router-link>

          <router-link
            to="/leads/create"
            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="text-sm font-medium text-gray-900">Add Lead</span>
          </router-link>

          <router-link
            to="/activities/create"
            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm font-medium text-gray-900">Schedule Activity</span>
          </router-link>

          <router-link
            to="/emails/create"
            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <svg class="w-5 h-5 text-purple-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-sm font-medium text-gray-900">Send Email</span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Lead Pipeline -->
      <div class="card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Pipeline</h3>
        <div class="space-y-3">
          <div v-for="stage in pipelineStages" :key="stage.name" class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">{{ stage.name }}</span>
            <div class="flex items-center space-x-2">
              <div class="w-24 bg-gray-200 rounded-full h-2">
                <div
                  class="bg-primary-600 h-2 rounded-full"
                  :style="{ width: stage.percentage + '%' }"
                ></div>
              </div>
              <span class="text-sm text-gray-500">{{ stage.count }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Contacts -->
      <div class="card">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Recent Contacts</h3>
          <router-link to="/contacts" class="text-sm text-primary-600 hover:text-primary-500">
            View all
          </router-link>
        </div>
        <div class="space-y-3">
          <div v-for="contact in recentContacts" :key="contact.id" class="flex items-center space-x-3">
            <img
              :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(contact.name)}&color=7F9CF5&background=EBF4FF`"
              :alt="contact.name"
              class="w-8 h-8 rounded-full"
            />
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">{{ contact.name }}</p>
              <p class="text-xs text-gray-500">{{ contact.email }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Reactive data
const stats = ref({
  contacts: 0,
  leads: 0,
  activities: 0,
  revenue: 0,
});

const recentActivities = ref([]);
const recentContacts = ref([]);
const pipelineStages = ref([]);

// Mock data - replace with actual API calls
const loadDashboardData = () => {
  // Mock stats
  stats.value = {
    contacts: 1247,
    leads: 89,
    activities: 23,
    revenue: 45600,
  };

  // Mock recent activities
  recentActivities.value = [
    { id: 1, title: 'Called John Doe about proposal', time: '2 hours ago' },
    { id: 2, title: 'Email sent to Acme Corp', time: '4 hours ago' },
    { id: 3, title: 'Meeting scheduled with Jane Smith', time: '1 day ago' },
    { id: 4, title: 'Lead created for Tech Solutions', time: '2 days ago' },
    { id: 5, title: 'Follow-up call completed', time: '3 days ago' },
  ];

  // Mock recent contacts
  recentContacts.value = [
    { id: 1, name: 'John Doe', email: 'john@example.com' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
    { id: 3, name: 'Bob Johnson', email: 'bob@example.com' },
    { id: 4, name: 'Alice Brown', email: 'alice@example.com' },
    { id: 5, name: 'Charlie Wilson', email: 'charlie@example.com' },
  ];

  // Mock pipeline stages
  pipelineStages.value = [
    { name: 'New', count: 25, percentage: 80 },
    { name: 'Qualified', count: 18, percentage: 60 },
    { name: 'Proposal', count: 12, percentage: 40 },
    { name: 'Negotiation', count: 8, percentage: 25 },
    { name: 'Closed Won', count: 5, percentage: 15 },
  ];
};

onMounted(() => {
  loadDashboardData();
});
</script>
