<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-6 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo and Title -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-sm">C</span>
            </div>
            <h1 class="text-xl font-semibold text-gray-900">Laravel CRM</h1>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="flex-1 max-w-lg mx-8">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              type="search"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
              placeholder="Search contacts, leads, activities..."
              v-model="searchQuery"
              @input="handleSearch"
            />
          </div>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
          <!-- Notifications -->
          <button class="p-2 text-gray-400 hover:text-gray-500 relative">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
            </svg>
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
          </button>

          <!-- User Dropdown -->
          <div class="relative" ref="userDropdown">
            <button
              @click="toggleUserMenu"
              class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              <img
                class="h-8 w-8 rounded-full"
                src="https://ui-avatars.com/api/?name=Admin+User&color=7F9CF5&background=EBF4FF"
                alt="User avatar"
              />
              <span class="hidden md:block text-gray-700 font-medium">Admin User</span>
              <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
              v-show="showUserMenu"
              class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
            >
              <router-link
                to="/profile"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Your Profile
              </router-link>
              <router-link
                to="/settings"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Settings
              </router-link>
              <div class="border-t border-gray-100"></div>
              <button
                @click="logout"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Sign out
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const searchQuery = ref('');
const showUserMenu = ref(false);
const userDropdown = ref(null);

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const handleSearch = () => {
  // Implement search functionality
  console.log('Searching for:', searchQuery.value);
};

const logout = () => {
  // Implement logout functionality
  console.log('Logging out...');
  showUserMenu.value = false;
};

const handleClickOutside = (event) => {
  if (userDropdown.value && !userDropdown.value.contains(event.target)) {
    showUserMenu.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
