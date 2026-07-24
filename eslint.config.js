import blumilkTypescript from '@blumilksoftware/eslint-config/typescript-config.js'

export default [
  ...blumilkTypescript,
  {
    ignores: ['resources/js/Types/ziggy.d.ts', 'resources/js/Types/ziggy-routes.d.ts'],
  },
]
