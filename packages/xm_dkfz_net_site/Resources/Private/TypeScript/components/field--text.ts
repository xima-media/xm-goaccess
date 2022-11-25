class FieldText {
    fieldEl

    constructor () {

        this.fieldEl = document.querySelectorAll('.field--text') as NodeListOf<HTMLElement>

        this.events()
    }

    events () {
        // variables
        const self = this

        // event listener
        self.fieldEl.forEach(fieldEl => {
            const fieldInputEl = fieldEl.querySelector('.field__input') as HTMLInputElement
            fieldInputEl.addEventListener('focus', () => self.focus(fieldEl, fieldInputEl))
            fieldInputEl.addEventListener('blur', () => self.blur(fieldEl, fieldInputEl))
        })
    }

    focus (fieldEl:HTMLElement, fieldInputEl: HTMLInputElement) {
        fieldEl.classList.add('fx--focus')
    }

    blur (fieldEl:HTMLElement, fieldInputEl: HTMLInputElement) {
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

export default (new FieldText())
