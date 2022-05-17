/**
 *    Debug
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
import './debug.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Debug {
    // @todo variablen wegbekommen
    debugEl
    buttonToggleMenuEl
    buttonGridEl

    constructor () {
        app.log('component "debug" loaded')
        const debugSetByUrlParam = app.getUrlParamater(window.location.search, 'debug')
        if (debugSetByUrlParam === 'true' || debugSetByUrlParam === '1') { app.debug = true }
        if (debugSetByUrlParam === 'false' || debugSetByUrlParam === '0') { app.debug = false }

        if (app.debug) {
            document.documentElement.classList.add('fx--debug')
            this.debugEl = document.querySelector('.debug-menu')
            this.buttonToggleMenuEl = document.querySelector('.debug-menu__button.fx--toggle-menu')
            this.buttonGridEl = document.querySelector('.debug-menu__button.fx--grid')

            // methods
            this.events()
        }
    }

    events () {
        const self = this

        // toggle debug menu
        self.buttonToggleMenuEl.addEventListener('click', () => self.toggleDebugMenu())

        // toggle grid
        self.buttonGridEl.addEventListener('click', () => self.toggleGrid())
    }

    toggleDebugMenu () {
        this.debugEl.classList.toggle('fx--open')
    }

    toggleGrid () {
        document.documentElement.classList.toggle('fx--debug-grid')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Debug())

// end of debug.js
