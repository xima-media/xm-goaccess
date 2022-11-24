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


/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class NavigationMain {
    buttonToggleMenuEl
    buttonToggleMenuItemsEl

    constructor () {
        app.log('component "main navigation" loaded')
        this.buttonToggleMenuEl = document.querySelector<HTMLButtonElement>('.fx--toggle-main-menu')
        this.buttonToggleMenuItemsEl = document.querySelectorAll<HTMLButtonElement>('.navigation__button--toggle-items')

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

        // toggle mobile menu
        self.buttonToggleMenuEl.addEventListener('click', () => self.toggleMobileMenu())

        // toggle mobile menu items
        self.buttonToggleMenuItemsEl.forEach(button => (
            button.addEventListener('click', () => self.toggleMobileMenuItems(button))
        ))
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

    /**
     * Toggle mobile menu items
     * @param button HTMLButtonElement
     */
    toggleMobileMenuItems (button: HTMLButtonElement) {
        //self.buttonToggleMenuEl @todo togle aria-attributwe

        //document.documentElement.classList.toggle('fx--main-menu-open')

        console.log(button)

        button.classList.toggle('fx--active')
    }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationMain())

// end of navigation--main.js
