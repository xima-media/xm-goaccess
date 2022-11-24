/**
 *    Field select
 *
 *    @tableofcontent
 *      1. Dependencies
 *       1.1 Import css
 *       1.2 Import js
 *      2. Class
 *      3. Export class
 *
 */

/**
 *     @section 1. Dependencies
 */

/** @section 1.1 Import css */
import './field--select.scss'
// import 'tom-select/dist/scss/tom-select.bootstrap5.scss'

/** @section 1.2 Import js */
import app from './basic'
import TomSelect from 'tom-select'

/**
 *     @section 2. Class
 */

class FieldSelect {
    fieldEl

    constructor () {
        app.log('component "field select" loaded')

        this.fieldEl = document.querySelectorAll('.field--select') as NodeListOf<HTMLElement>

        // methods
        this.init()
    }

    /**
     * Init
     */
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

    /**
     * Focus input
     */
    focus (fieldEl:HTMLElement, fieldInputEl: HTMLSelectElement) {
        app.log('Focus', '⌨️')

        // add 'focus' css class
        fieldEl.classList.add('fx--focus')
    }

    /**
     * Change input
     */
    change (fieldEl:HTMLElement, fieldInputEl: HTMLSelectElement) {
        app.log('Change', '⌨️')

        // add 'changed' css class
        fieldEl.classList.add('fx--changed')
    }


    /**
     * Blur input
     */
    blur (fieldEl:HTMLElement, fieldInputEl: HTMLSelectElement) {
        app.log('Blur', '🎖️')

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

/**
 *     @section 3. Export class
 */

export default (new FieldSelect())

// end of field--select.js
