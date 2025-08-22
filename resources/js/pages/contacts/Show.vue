<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900">{{ contact.name }}</h1>
      <div class="flex space-x-3">
        <router-link to="/contacts" class="btn btn-secondary">
          Back to Contacts
        </router-link>
        <router-link :to="`/contacts/${contact.id}/edit`" class="btn btn-primary">
          Edit Contact
        </router-link>
      </div>
    </div>

    <!-- Contact Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Info -->
      <div class="lg:col-span-2 space-y-6">
        <div class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
          <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <dt class="text-sm font-medium text-gray-500">Type</dt>
              <dd class="mt-1">
                <span class="badge" :class="contact.type === 'person' ? 'badge-blue' : 'badge-green'">
                  {{ contact.type }}
                </span>
              </dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Email</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ contact.email || 'N/A' }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Phone</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ contact.phone || 'N/A' }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Website</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ contact.website || 'N/A' }}</dd>
            </div>
          </dl>
        </div>

        <!-- Address -->
        <div class="card" v-if="hasAddress">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Address</h3>
          <div class="text-sm text-gray-900">
            <div v-if="contact.address.street">{{ contact.address.street }}</div>
            <div>
              {{ contact.address.city }}{{ contact.address.state ? ', ' + contact.address.state : '' }}
              {{ contact.address.zip }}
            </div>
            <div v-if="contact.address.country">{{ contact.address.country }}</div>
          </div>
        </div>

        <!-- Notes -->
        <div class="card" v-if="contact.notes">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
          <p class="text-sm text-gray-700">{{ contact.notes }}</p>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
          <div class="space-y-2">
            <button class="btn btn-sm btn-primary w-full">Send Email</button>
            <button class="btn btn-sm btn-secondary w-full">Schedule Call</button>
            <button class="btn btn-sm btn-secondary w-full">Add Note</button>
            <button class="btn btn-sm btn-secondary w-full">Create Lead</button>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h3>
          <div class="space-y-3">
            <div v-for="activity in recentActivities" :key="activity.id" class="text-sm">
              <div class="font-medium text-gray-900">{{ activity.title }}</div>
              <div class="text-gray-500">{{ formatDate(activity.created_at) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const contact = ref({});
const recentActivities = ref([]);

const hasAddress = computed(() => {
  const addr = contact.value.address || {};
  return addr.street || addr.city || addr.state || addr.zip || addr.country;
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const loadContact = () => {
  // Mock data - replace with actual API call
  contact.value = {
    id: route.params.id,
    name: 'John Doe',
    type: 'person',
    email: 'john@example.com',
    phone: '+1 234 567 8900',
    website: 'https://johndoe.com',
    address: {
      street: '123 Main St',
      city: 'New York',
      state: 'NY',
      zip: '10001',
      country: 'USA'
    },
    notes: 'Important client with high potential for future business.',
    created_at: '2024-01-15T10:30:00Z'
  };

  recentActivities.value = [
    {
      id: 1,
      title: 'Email sent',
      created_at: '2024-01-20T14:30:00Z'
    },
    {
      id: 2,
      title: 'Phone call completed',
      created_at: '2024-01-18T10:15:00Z'
    }
  ];
};

onMounted(() => {
  loadContact();
});
</script>
