<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900">Edit Contact</h1>
      <div class="flex space-x-3">
        <router-link :to="`/contacts/${contact.id}`" class="btn btn-secondary">
          Cancel
        </router-link>
        <button @click="deleteContact" class="btn btn-danger">
          Delete
        </button>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="card">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Contact Type -->
        <div>
          <label class="form-label">Contact Type</label>
          <div class="flex space-x-4">
            <label class="flex items-center">
              <input
                type="radio"
                value="person"
                v-model="form.type"
                class="form-radio"
              />
              <span class="ml-2">Person</span>
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                value="organization"
                v-model="form.type"
                class="form-radio"
              />
              <span class="ml-2">Organization</span>
            </label>
          </div>
        </div>

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="form-label">
              {{ form.type === 'person' ? 'Full Name' : 'Organization Name' }}
            </label>
            <input
              type="text"
              v-model="form.name"
              class="form-input"
              required
            />
          </div>

          <div>
            <label class="form-label">Email</label>
            <input
              type="email"
              v-model="form.email"
              class="form-input"
            />
          </div>

          <div>
            <label class="form-label">Phone</label>
            <input
              type="tel"
              v-model="form.phone"
              class="form-input"
            />
          </div>

          <div>
            <label class="form-label">Website</label>
            <input
              type="url"
              v-model="form.website"
              class="form-input"
            />
          </div>
        </div>

        <!-- Address -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 mb-4">Address</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label class="form-label">Street Address</label>
              <input
                type="text"
                v-model="form.address.street"
                class="form-input"
              />
            </div>

            <div>
              <label class="form-label">City</label>
              <input
                type="text"
                v-model="form.address.city"
                class="form-input"
              />
            </div>

            <div>
              <label class="form-label">State/Province</label>
              <input
                type="text"
                v-model="form.address.state"
                class="form-input"
              />
            </div>

            <div>
              <label class="form-label">ZIP/Postal Code</label>
              <input
                type="text"
                v-model="form.address.zip"
                class="form-input"
              />
            </div>

            <div>
              <label class="form-label">Country</label>
              <input
                type="text"
                v-model="form.address.country"
                class="form-input"
              />
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="form-label">Notes</label>
          <textarea
            v-model="form.notes"
            rows="4"
            class="form-textarea"
            placeholder="Additional notes about this contact..."
          ></textarea>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
          <router-link :to="`/contacts/${contact.id}`" class="btn btn-secondary">
            Cancel
          </router-link>
          <button type="submit" class="btn btn-primary">
            Update Contact
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const contact = ref({});
const form = ref({
  type: 'person',
  name: '',
  email: '',
  phone: '',
  website: '',
  address: {
    street: '',
    city: '',
    state: '',
    zip: '',
    country: ''
  },
  notes: ''
});

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
    notes: 'Important client with high potential for future business.'
  };

  // Populate form with contact data
  form.value = { ...contact.value };
};

const submitForm = () => {
  // TODO: Implement API call to update contact
  console.log('Updating contact:', form.value);
  
  // Simulate success and redirect
  router.push(`/contacts/${contact.value.id}`);
};

const deleteContact = () => {
  if (confirm('Are you sure you want to delete this contact?')) {
    // TODO: Implement API call to delete contact
    console.log('Deleting contact:', contact.value.id);
    
    // Simulate success and redirect
    router.push('/contacts');
  }
};

onMounted(() => {
  loadContact();
});
</script>
