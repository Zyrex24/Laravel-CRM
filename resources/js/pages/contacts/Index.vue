<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900">Contacts</h1>
      <router-link to="/contacts/create" class="btn btn-primary">
        Add Contact
      </router-link>
    </div>

    <!-- Search and Filters -->
    <div class="card">
      <div class="flex items-center space-x-4">
        <div class="flex-1">
          <input
            type="search"
            class="form-input"
            placeholder="Search contacts..."
            v-model="searchQuery"
          />
        </div>
        <select class="form-select">
          <option value="">All Types</option>
          <option value="person">People</option>
          <option value="organization">Organizations</option>
        </select>
      </div>
    </div>

    <!-- Contacts Table -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="contact in contacts" :key="contact.id">
              <td>
                <div class="flex items-center space-x-3">
                  <img
                    :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(contact.name)}&color=7F9CF5&background=EBF4FF`"
                    :alt="contact.name"
                    class="w-8 h-8 rounded-full"
                  />
                  <span class="font-medium">{{ contact.name }}</span>
                </div>
              </td>
              <td>
                <span class="badge" :class="contact.type === 'person' ? 'badge-blue' : 'badge-green'">
                  {{ contact.type }}
                </span>
              </td>
              <td>{{ contact.email }}</td>
              <td>{{ contact.phone }}</td>
              <td>{{ formatDate(contact.created_at) }}</td>
              <td>
                <div class="flex items-center space-x-2">
                  <router-link :to="`/contacts/${contact.id}`" class="btn btn-sm btn-secondary">
                    View
                  </router-link>
                  <router-link :to="`/contacts/${contact.id}/edit`" class="btn btn-sm btn-primary">
                    Edit
                  </router-link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const searchQuery = ref('');
const contacts = ref([]);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const loadContacts = () => {
  // Mock data - replace with actual API call
  contacts.value = [
    {
      id: 1,
      name: 'John Doe',
      type: 'person',
      email: 'john@example.com',
      phone: '+1 234 567 8900',
      created_at: '2024-01-15T10:30:00Z'
    },
    {
      id: 2,
      name: 'Acme Corporation',
      type: 'organization',
      email: 'info@acme.com',
      phone: '+1 234 567 8901',
      created_at: '2024-01-14T14:20:00Z'
    },
    {
      id: 3,
      name: 'Jane Smith',
      type: 'person',
      email: 'jane@example.com',
      phone: '+1 234 567 8902',
      created_at: '2024-01-13T09:15:00Z'
    }
  ];
};

onMounted(() => {
  loadContacts();
});
</script>
