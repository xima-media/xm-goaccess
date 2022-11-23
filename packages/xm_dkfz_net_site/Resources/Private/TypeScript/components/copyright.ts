/**
 *    Copyright
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
import './copyright.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class Copyright {
    copyrightButtonEL

    constructor () {
        app.log('component "copyright" loaded')
        this.copyrightButtonEL = document.querySelectorAll<HTMLElement>('.copyright__button')

        // methods
        // this.events()
    }

    /**
     * Events
     */
    events () {
        const self = this

        // on click
        self.copyrightButtonEL.forEach((buttonEl) => buttonEl.addEventListener('click', () => self.toggleCopyright(buttonEl)))
    }

    /**
     * Toggle copyright
     * @param buttonEl
     */
    toggleCopyright (buttonEl: HTMLElement) {
        // toggle css class
        buttonEl.classList.toggle('fx--active')

        // @todo bitv aria-expanded togglen
    }
}

/**
 *     @section 3. Export class
 */

export default (new Copyright())

// end of copyright.js
