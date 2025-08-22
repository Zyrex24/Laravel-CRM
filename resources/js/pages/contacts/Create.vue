<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900">Create Contact</h1>
      <router-link to="/contacts" class="btn btn-secondary">
        Back to Contacts
      </router-link>
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
          <router-link to="/contacts" class="btn btn-secondary">
            Cancel
          </router-link>
          <button type="submit" class="btn btn-primary">
            Create Contact
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

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

const submitForm = () => {
  // TODO: Implement API call to create contact
  console.log('Creating contact:', form.value);
  
  // Simulate success and redirect
  router.push('/contacts');
};
</script>
