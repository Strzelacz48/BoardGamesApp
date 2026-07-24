<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import SiteFooter from '@/Components/SiteFooter.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="flex min-h-screen flex-col bg-gray-100 dark:bg-gray-900">
    <div class="absolute right-4 top-4">
      <LanguageSwitcher />
    </div>

    <div class="flex flex-1 flex-col items-center pt-6 sm:justify-center sm:pt-0">
      <div>
        <Link href="/">
          <ApplicationLogo />
        </Link>
      </div>

      <div
        class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800"
      >
        <slot />
      </div>
    </div>

    <SiteFooter />
  </div>
</template>
