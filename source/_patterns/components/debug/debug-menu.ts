/**
 *    Debug menu
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
import './debug-menu.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class DebugMenu {
    // @todo variablen wegbekommen
    debugEl
    buttonToggleMenuEl
    buttonToggleSubMenuEl

    constructor () {
        app.log('component "debug" loaded')
        const debugSetByUrlParam = app.getUrlParamater(window.location.search, 'debug')
        if (debugSetByUrlParam === 'true' || debugSetByUrlParam === '1') { app.debug = true }
        if (debugSetByUrlParam === 'false' || debugSetByUrlParam === '0') { app.debug = false }

        if (app.debug) {
            document.documentElement.classList.add('fx--debug')
            this.debugEl = document.querySelector('.debug-menu')
            this.buttonToggleMenuEl = document.querySelector<HTMLButtonElement>('.debug-menu__button.fx--toggle-menu')
            this.buttonToggleSubMenuEl = document.querySelectorAll<HTMLButtonElement>('.fx--toggle-sub-menu')

            // methods
            this.events()
        }
    }

    /**
     * Events
     */
    events () {
        const self = this

        // toggle: debug menu
        self.buttonToggleMenuEl.addEventListener('click', () => self.toggleMenuItems(self.buttonToggleMenuEl))

        // toggle: submenu item
        self.buttonToggleSubMenuEl.forEach(buttonEl => (
            buttonEl.addEventListener('click', () => self.toggleMenuItems(buttonEl))
        ))
    }

    /**
     * Open/close menu items
     */
    toggleMenuItems (targetEl: HTMLButtonElement) {
        // toggle 'open' class
        targetEl.classList.toggle('fx--open')
    }
}

/**
 *     @section 3. Export class
 */

export default (new DebugMenu())

// end of debug-menu.js
