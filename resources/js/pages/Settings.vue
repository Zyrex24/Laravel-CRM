<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
      <p class="text-gray-600">Manage your CRM system configuration</p>
    </div>

    <!-- Settings Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Navigation -->
      <div class="lg:col-span-1">
        <nav class="space-y-1">
          <button
            v-for="section in sections"
            :key="section.id"
            @click="activeSection = section.id"
            class="w-full text-left px-3 py-2 rounded-md text-sm font-medium transition-colors"
            :class="activeSection === section.id 
              ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
          >
            {{ section.name }}
          </button>
        </nav>
      </div>

      <!-- Content -->
      <div class="lg:col-span-2">
        <!-- General Settings -->
        <div v-if="activeSection === 'general'" class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
          <div class="space-y-4">
            <div>
              <label class="form-label">Company Name</label>
              <input type="text" v-model="settings.company_name" class="form-input" />
            </div>
            <div>
              <label class="form-label">Default Currency</label>
              <select v-model="settings.currency" class="form-select">
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="GBP">GBP - British Pound</option>
              </select>
            </div>
            <div>
              <label class="form-label">Time Zone</label>
              <select v-model="settings.timezone" class="form-select">
                <option value="UTC">UTC</option>
                <option value="America/New_York">Eastern Time</option>
                <option value="America/Chicago">Central Time</option>
                <option value="America/Los_Angeles">Pacific Time</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Email Settings -->
        <div v-if="activeSection === 'email'" class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Email Settings</h3>
          <div class="space-y-4">
            <div>
              <label class="form-label">SMTP Host</label>
              <input type="text" v-model="settings.smtp_host" class="form-input" />
            </div>
            <div>
              <label class="form-label">SMTP Port</label>
              <input type="number" v-model="settings.smtp_port" class="form-input" />
            </div>
            <div>
              <label class="form-label">SMTP Username</label>
              <input type="text" v-model="settings.smtp_username" class="form-input" />
            </div>
            <div>
              <label class="form-label">From Email</label>
              <input type="email" v-model="settings.from_email" class="form-input" />
            </div>
          </div>
        </div>

        <!-- User Management -->
        <div v-if="activeSection === 'users'" class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">User Management</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-medium">Allow User Registration</h4>
                <p class="text-sm text-gray-600">Allow new users to register accounts</p>
              </div>
              <input type="checkbox" v-model="settings.allow_registration" class="form-checkbox" />
            </div>
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-medium">Require Email Verification</h4>
                <p class="text-sm text-gray-600">Users must verify their email address</p>
              </div>
              <input type="checkbox" v-model="settings.require_email_verification" class="form-checkbox" />
            </div>
          </div>
        </div>

        <!-- Security -->
        <div v-if="activeSection === 'security'" class="card">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
          <div class="space-y-4">
            <div>
              <label class="form-label">Session Timeout (minutes)</label>
              <input type="number" v-model="settings.session_timeout" class="form-input" />
            </div>
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-medium">Two-Factor Authentication</h4>
                <p class="text-sm text-gray-600">Require 2FA for all users</p>
              </div>
              <input type="checkbox" v-model="settings.require_2fa" class="form-checkbox" />
            </div>
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-medium">Password Complexity</h4>
                <p class="text-sm text-gray-600">Enforce strong password requirements</p>
              </div>
              <input type="checkbox" v-model="settings.password_complexity" class="form-checkbox" />
            </div>
          </div>
        </div>

        <!-- Save Button -->
        <div class="mt-6">
          <button @click="saveSettings" class="btn btn-primary">
            Save Settings
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const activeSection = ref('general');

const sections = [
  { id: 'general', name: 'General' },
  { id: 'email', name: 'Email' },
  { id: 'users', name: 'User Management' },
  { id: 'security', name: 'Security' }
];

const settings = ref({
  company_name: 'Laravel CRM',
  currency: 'USD',
  timezone: 'UTC',
  smtp_host: '',
  smtp_port: 587,
  smtp_username: '',
  from_email: '',
  allow_registration: false,
  require_email_verification: true,
  session_timeout: 120,
  require_2fa: false,
  password_complexity: true
});

const loadSettings = () => {
  // Mock data - replace with actual API call
  console.log('Loading settings...');
};

const saveSettings = () => {
  // TODO: Implement API call to save settings
  console.log('Saving settings:', settings.value);
  alert('Settings saved successfully!');
};

onMounted(() => {
  loadSettings();
});
</script>
