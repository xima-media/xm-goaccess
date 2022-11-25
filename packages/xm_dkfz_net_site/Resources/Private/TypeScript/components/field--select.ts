import app from './basic'
import TomSelect from 'tom-select'

class FieldSelect {
  fieldEl

  constructor () {
    this.fieldEl = document.querySelectorAll('.field--select') as NodeListOf<HTMLElement>

    // methods
    this.init()
  }

  init () {
    // variables
    const self = this

    // get every select
    self.fieldEl.forEach((fieldEl) => {
      const fieldInputEl = fieldEl.querySelector('.field__input') as HTMLSelectElement

      // init tom select
      new TomSelect(fieldInputEl, {
        onFocus: () => {
          self.focus(fieldEl, fieldInputEl)
        },
        onChange: () => {
          self.change(fieldEl, fieldInputEl)
        },
        onBlur: () => {
          self.blur(fieldEl, fieldInputEl)
        }
      })
    })
  }

  focus (fieldEl: HTMLElement, fieldInputEl: HTMLSelectElement) {
    fieldEl.classList.add('fx--focus')
  }

  change (fieldEl: HTMLElement, fieldInputEl: HTMLSelectElement) {
    fieldEl.classList.add('fx--changed')
  }

  blur (fieldEl: HTMLElement, fieldInputEl: HTMLSelectElement) {
    // toggle 'filled' css class
    if (fieldInputEl.value) {
      fieldEl.classList.add('fx--filled')
    } else {
      fieldEl.classList.remove('fx--filled')
    }

    // remove 'focus' css class
    fieldEl.classList.remove('fx--focus')
  }
}

export default (new FieldSelect())
