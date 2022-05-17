/**
 *    Navigation
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
import './navigation.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Navigation {
    buttonToggleMenuEl

    constructor () {
        app.log('component "navigation" loaded')
        this.buttonToggleMenuEl = document.querySelector('.navigation__button.fx--toggle')

        if (this.buttonToggleMenuEl) {
            // methods
            this.events()
        }
    }

    events () {
        const self = this

        // toggle debug menu
        self.buttonToggleMenuEl.addEventListener('click', () => self.toggleMobileMenu())
    }

    toggleMobileMenu () {
        const self = this

        // @todo focus-trap hinzufügen
        //self.buttonToggleMenuEl @todo togle aria-attributwe

        document.documentElement.classList.toggle('fx--main-menu-open')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Navigation())

// end of navigation.js
