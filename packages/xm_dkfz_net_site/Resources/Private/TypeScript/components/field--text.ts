/**
 *    Field text
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
import './field--text.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class FieldText {
    fieldEl

    constructor () {
        app.log('component "field text" loaded')

        this.fieldEl = document.querySelectorAll<HTMLElement>('.field--text')

        // methods
        this.events()
    }

    /**
     * Events
     */
    events () {
        // variables
        const self = this

        // event listener
        self.fieldEl.forEach(fieldEl => {
            const fieldInputEl = fieldEl.querySelector<HTMLInputElement>('.field__input')
            fieldInputEl.addEventListener('focus', () => self.focus(fieldEl, fieldInputEl))
            fieldInputEl.addEventListener('blur', () => self.blur(fieldEl, fieldInputEl))
        })
    }

    /**
     * Focus input
     */
    focus (fieldEl:HTMLElement, fieldInputEl: HTMLInputElement) {
        app.log('Focus', '⌨️')

        // add 'focus' css class
        fieldEl.classList.add('fx--focus')
    }

    /**
     * Blur input
     */
    blur (fieldEl:HTMLElement, fieldInputEl: HTMLInputElement) {
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

export default (new FieldText())

// end of field--text.js
