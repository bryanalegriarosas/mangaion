import Swal from 'sweetalert2'
import type { SweetAlertOptions, SweetAlertResult } from 'sweetalert2'

// ─── Tipos ────────────────────────────────────────────────────────
type AlertResult = Promise<SweetAlertResult<unknown>>

// ─── Helper interno para mergear opciones sin que TS se queje ────
// SweetAlert2 usa uniones discriminadas muy estrictas en sus tipos,
// el spread genera un tipo que TS no puede resolver — el cast es necesario.
function merge(...opts: Partial<SweetAlertOptions>[]): SweetAlertOptions {
  return Object.assign({}, ...opts) as SweetAlertOptions
}

// ─── Configuración global por defecto ────────────────────────────
const defaultOptions: Partial<SweetAlertOptions> = {
  toast:                  false,
  position:               'center',
  showConfirmButton:      true,
  showCancelButton:       false,
  confirmButtonText:      'Aceptar',
  cancelButtonText:       'Cancelar',
  confirmButtonColor:     '#3b82f6',
  cancelButtonColor:      '#ef4444',
  background:             '#ffffff',
  color:                  '#1f2937',
  backdrop:               true,
  allowOutsideClick:      false,
  allowEscapeKey:         true,
  allowEnterKey:          true,
  stopKeydownPropagation: false,
  reverseButtons:         true,
  focusConfirm:           true,
  returnFocus:            true,
}

// ─── Alert service ────────────────────────────────────────────────
export const alertService = {

  success(title: string, text?: string, options?: Partial<SweetAlertOptions>): AlertResult {
    return Swal.fire(merge(defaultOptions, { icon: 'success', title, text }, options ?? {}))
  },

  error(title: string, text?: string, options?: Partial<SweetAlertOptions>): AlertResult {
    return Swal.fire(merge(defaultOptions, { icon: 'error', title, text }, options ?? {}))
  },

  warning(title: string, text?: string, options?: Partial<SweetAlertOptions>): AlertResult {
    return Swal.fire(merge(defaultOptions, { icon: 'warning', title, text }, options ?? {}))
  },

  info(title: string, text?: string, options?: Partial<SweetAlertOptions>): AlertResult {
    return Swal.fire(merge(defaultOptions, { icon: 'info', title, text }, options ?? {}))
  },

  confirm(title: string, text?: string, options?: Partial<SweetAlertOptions>): AlertResult {
    return Swal.fire(merge(defaultOptions, {
      icon:              'question',
      title,
      text,
      showCancelButton:  true,
      confirmButtonText: 'Sí',
      cancelButtonText:  'No',
    }, options ?? {}))
  },

  loading(title: string, text?: string): AlertResult {
    return Swal.fire(merge(defaultOptions, {
      title,
      text,
      allowOutsideClick: false,
      allowEscapeKey:    false,
      allowEnterKey:     false,
      showConfirmButton: false,
      didOpen:           () => { Swal.showLoading() },
    }))
  },

  close(): void {
    Swal.close()
  },

  custom(options: Partial<SweetAlertOptions>): AlertResult {
    return Swal.fire(merge(defaultOptions, options))
  },

  toast: {
    success(title: string, timer = 3000): AlertResult {
      return Swal.fire(merge({
        toast: true, position: 'top-end', icon: 'success',
        title, showConfirmButton: false, timer, timerProgressBar: true,
        didOpen: (el) => {
          el.addEventListener('mouseenter', Swal.stopTimer)
          el.addEventListener('mouseleave', Swal.resumeTimer)
        },
      }))
    },

    error(title: string, timer = 3000): AlertResult {
      return Swal.fire(merge({
        toast: true, position: 'top-end', icon: 'error',
        title, showConfirmButton: false, timer, timerProgressBar: true,
        didOpen: (el) => {
          el.addEventListener('mouseenter', Swal.stopTimer)
          el.addEventListener('mouseleave', Swal.resumeTimer)
        },
      }))
    },

    warning(title: string, timer = 3000): AlertResult {
      return Swal.fire(merge({
        toast: true, position: 'top-end', icon: 'warning',
        title, showConfirmButton: false, timer, timerProgressBar: true,
        didOpen: (el) => {
          el.addEventListener('mouseenter', Swal.stopTimer)
          el.addEventListener('mouseleave', Swal.resumeTimer)
        },
      }))
    },

    info(title: string, timer = 3000): AlertResult {
      return Swal.fire(merge({
        toast: true, position: 'top-end', icon: 'info',
        title, showConfirmButton: false, timer, timerProgressBar: true,
        didOpen: (el) => {
          el.addEventListener('mouseenter', Swal.stopTimer)
          el.addEventListener('mouseleave', Swal.resumeTimer)
        },
      }))
    },
  },
}

export { Swal }
export default alertService;