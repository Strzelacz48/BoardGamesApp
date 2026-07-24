import type { ZiggyVue } from '../../vendor/tightenco/ziggy'

type ZiggyConfig = NonNullable<Parameters<typeof ZiggyVue.install>[1]>

declare global {
  interface Window {
    Ziggy: ZiggyConfig
  }
}

export const Ziggy = window.Ziggy
