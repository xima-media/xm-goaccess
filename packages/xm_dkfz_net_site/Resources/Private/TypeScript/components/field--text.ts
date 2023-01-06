class FieldText {
  fieldEl

  constructor() {
    this.fieldEl = document.querySelectorAll<HTMLInputElement>('.field--text')

    this.events()
  }

  events() {
    // event listener
    this.fieldEl.forEach(fieldEl => {
      const fieldInputEl = fieldEl.querySelector<HTMLInputElement>('.field__input')
      fieldInputEl?.addEventListener('focus', () => {
        this.focus(fieldEl)
      })
      fieldInputEl?.addEventListener('blur', () => {
        this.blur(fieldEl, fieldInputEl)
      })
    })
  }

  focus(fieldEl: HTMLElement) {
    fieldEl.classList.add('fx--focus')
  }

  blur(fieldEl: HTMLElement, fieldInputEl: HTMLInputElement) {
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

export default new FieldText()
