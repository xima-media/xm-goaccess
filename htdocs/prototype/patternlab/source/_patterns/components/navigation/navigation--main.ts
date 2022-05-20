/**
 *    Main navigation
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
import './navigation--main.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class NavigationMain {
    buttonToggleMenuEl

    constructor () {
        app.log('component "main navigation" loaded')
        this.buttonToggleMenuEl = document.querySelector('.navigation__button.fx--toggle')

        if (this.buttonToggleMenuEl) {
            // methods
            this.events()
        }
    }

    /**
     * Events
     */
    events () {
        const self = this

        // toggle debug menu
        self.buttonToggleMenuEl.addEventListener('click', () => self.toggleMobileMenu())
    }

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu () {
        // const self = this

        // @todo focus-trap hinzufügen
        //self.buttonToggleMenuEl @todo togle aria-attributwe

        document.documentElement.classList.toggle('fx--main-menu-open')
    }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationMain())

// end of navigation--main.js
