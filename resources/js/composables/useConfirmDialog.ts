import { ref } from 'vue'

export interface ConfirmDialogOptions {
  title: string
  message: string
  confirmLabel: string
  /** Omit to render an alert-style dialog with only a confirm/OK button. */
  cancelLabel?: string
  variant?: 'danger' | 'neutral'
}

const isOpen = ref(false)
const options = ref<ConfirmDialogOptions>({
  title: '',
  message: '',
  confirmLabel: 'Confirm',
  cancelLabel: 'Cancel',
  variant: 'neutral',
})

let resolveFn: ((value: boolean) => void) | null = null

export function useConfirmDialog() {
  function confirm(opts: ConfirmDialogOptions): Promise<boolean> {
    options.value = { variant: 'neutral', cancelLabel: 'Cancel', ...opts }
    isOpen.value = true

    return new Promise<boolean>((resolve) => {
      resolveFn = resolve
    })
  }

  /** Alert-style dialog: single confirm/OK button, no cancel option. */
  function alert({
    confirmLabel = 'OK',
    ...opts
  }: Omit<ConfirmDialogOptions, 'cancelLabel' | 'confirmLabel'> & { confirmLabel?: string }): Promise<void> {
    return confirm({ ...opts, confirmLabel, cancelLabel: undefined }).then(() => undefined)
  }

  function onConfirm(): void {
    isOpen.value = false
    resolveFn?.(true)
    resolveFn = null
  }

  function onCancel(): void {
    isOpen.value = false
    resolveFn?.(false)
    resolveFn = null
  }

  return { isOpen, options, confirm, alert, onConfirm, onCancel }
}
