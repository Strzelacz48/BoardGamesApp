<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate'
import { IconBrandGithub } from '@tabler/icons-vue'

const { t } = useTranslate()
</script>

<template>
  <footer class="border-t border-gray-200 py-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-500">
    <a
      href="https://github.com/Strzelacz48/BoardGamesApp"
      target="_blank"
      rel="noopener noreferrer"
      class="inline-flex items-center gap-1.5 transition hover:text-indigo-600 dark:hover:text-indigo-400"
    >
      <IconBrandGithub :size="18" :stroke-width="1.5" />
      {{ t('footer.sourceCode') }}
    </a>
  </footer>
</template>
