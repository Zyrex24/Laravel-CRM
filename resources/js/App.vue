<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <AppHeader />
    <div class="flex">
      <AppSidebar />
      <main class="flex-1 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import AppHeader from './components/layout/AppHeader.vue';
import AppSidebar from './components/layout/AppSidebar.vue';
</script>
