<template>
  <aside class="w-64 bg-white shadow-sm border-r border-gray-200 min-h-screen">
    <nav class="mt-5 px-2">
      <div class="space-y-1">
        <!-- Dashboard -->
        <router-link
          to="/"
          class="nav-item"
          :class="{ 'nav-item-active': $route.name === 'dashboard' }"
        >
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
          </svg>
          Dashboard
        </router-link>

        <!-- Contacts -->
        <div class="nav-group">
          <button
            @click="toggleSection('contacts')"
            class="nav-item w-full flex items-center justify-between"
            :class="{ 'nav-item-active': isContactsActive }"
          >
            <div class="flex items-center">
              <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              Contacts
            </div>
            <svg
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-90': expandedSections.contacts }"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-show="expandedSections.contacts" class="ml-6 mt-1 space-y-1">
            <router-link to="/contacts" class="nav-subitem">All Contacts</router-link>
            <router-link to="/contacts/create" class="nav-subitem">Add Contact</router-link>
          </div>
        </div>

        <!-- Leads -->
        <div class="nav-group">
          <button
            @click="toggleSection('leads')"
            class="nav-item w-full flex items-center justify-between"
            :class="{ 'nav-item-active': isLeadsActive }"
          >
            <div class="flex items-center">
              <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
              Leads
            </div>
            <svg
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-90': expandedSections.leads }"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-show="expandedSections.leads" class="ml-6 mt-1 space-y-1">
            <router-link to="/leads" class="nav-subitem">All Leads</router-link>
            <router-link to="/leads/create" class="nav-subitem">Add Lead</router-link>
          </div>
        </div>

        <!-- Activities -->
        <div class="nav-group">
          <button
            @click="toggleSection('activities')"
            class="nav-item w-full flex items-center justify-between"
            :class="{ 'nav-item-active': isActivitiesActive }"
          >
            <div class="flex items-center">
              <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
              Activities
            </div>
            <svg
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-90': expandedSections.activities }"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-show="expandedSections.activities" class="ml-6 mt-1 space-y-1">
            <router-link to="/activities" class="nav-subitem">All Activities</router-link>
            <router-link to="/activities/create" class="nav-subitem">Add Activity</router-link>
          </div>
        </div>

        <!-- Emails -->
        <div class="nav-group">
          <button
            @click="toggleSection('emails')"
            class="nav-item w-full flex items-center justify-between"
            :class="{ 'nav-item-active': isEmailsActive }"
          >
            <div class="flex items-center">
              <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              Emails
            </div>
            <svg
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-90': expandedSections.emails }"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-show="expandedSections.emails" class="ml-6 mt-1 space-y-1">
            <router-link to="/emails" class="nav-subitem">All Emails</router-link>
            <router-link to="/emails/create" class="nav-subitem">Compose</router-link>
            <router-link to="/emails/templates" class="nav-subitem">Templates</router-link>
          </div>
        </div>

        <!-- Users -->
        <div class="nav-group">
          <button
            @click="toggleSection('users')"
            class="nav-item w-full flex items-center justify-between"
            :class="{ 'nav-item-active': isUsersActive }"
          >
            <div class="flex items-center">
              <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
              Users
            </div>
            <svg
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-90': expandedSections.users }"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-show="expandedSections.users" class="ml-6 mt-1 space-y-1">
            <router-link to="/users" class="nav-subitem">All Users</router-link>
            <router-link to="/users/create" class="nav-subitem">Add User</router-link>
          </div>
        </div>

        <!-- Settings -->
        <router-link
          to="/settings"
          class="nav-item"
          :class="{ 'nav-item-active': $route.name === 'settings' }"
        >
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Settings
        </router-link>
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const expandedSections = ref({
  contacts: false,
  leads: false,
  activities: false,
  emails: false,
  users: false,
});

const toggleSection = (section) => {
  expandedSections.value[section] = !expandedSections.value[section];
};

// Computed properties to determine active states
const isContactsActive = computed(() => {
  return route.path.startsWith('/contacts');
});

const isLeadsActive = computed(() => {
  return route.path.startsWith('/leads');
});

const isActivitiesActive = computed(() => {
  return route.path.startsWith('/activities');
});

const isEmailsActive = computed(() => {
  return route.path.startsWith('/emails');
});

const isUsersActive = computed(() => {
  return route.path.startsWith('/users');
});

// Auto-expand sections based on current route
const autoExpandSections = () => {
  if (isContactsActive.value) expandedSections.value.contacts = true;
  if (isLeadsActive.value) expandedSections.value.leads = true;
  if (isActivitiesActive.value) expandedSections.value.activities = true;
  if (isEmailsActive.value) expandedSections.value.emails = true;
  if (isUsersActive.value) expandedSections.value.users = true;
};

// Initialize expanded sections
autoExpandSections();
</script>

<style scoped>
.nav-item {
  @apply text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150;
}

.nav-item-active {
  @apply bg-primary-50 border-r-2 border-primary-500 text-primary-700;
}

.nav-icon {
  @apply text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-5 w-5;
}

.nav-item-active .nav-icon {
  @apply text-primary-500;
}

.nav-subitem {
  @apply text-gray-500 hover:text-gray-700 hover:bg-gray-50 block px-2 py-1 text-sm rounded-md transition-colors duration-150;
}

.nav-group {
  @apply space-y-1;
}
</style>
