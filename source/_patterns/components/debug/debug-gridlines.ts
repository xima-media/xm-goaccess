/**
 *    Debug gridlines
 *
 *    @tableofcontent
 *      1. Dependencies
 *      2. Class
 *      3. Export class
 *
 */

/**
 *     @section 1. Dependencies
 */

// import css dependencies
import './debug-gridlines.scss'

// import js dependencies
// import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class DebugGridlines {
    gridlinesOn: boolean
    toggleButtonEl

    constructor () {
        // variables
        this.gridlinesOn = false
        this.toggleButtonEl = document.querySelectorAll('.debug-menu__button.fx--grid')

        // methods
        this.init()
        this.events()
    }

    /**
     * Init
     */
    init () {
        // variables
        const self = this

        // check local storage
        if (localStorage.getItem('gridlines') === 'true') {
            self.toggleGridlines(true)
        }
    }

    /**
     * Events
     */
    events () {
        // variables
        const self = this

        // event listener
        self.toggleButtonEl.forEach(button => (
            button.addEventListener('click', () => self.toggleGridlines())
        ))
    }

    /**
     * Show/hide gridlines
     */
    toggleGridlines (forceGridlines = false) {
        // variables
        const self = this

        if (forceGridlines) {
            // add 'active' class at toggle button
            self.toggleButtonEl.forEach(button => (
                button.classList.add('fx--active')
            ))

            // toggle 'gridlines' class at document root element
            document.documentElement.classList.add('fx--debug-grid')

            // set true
            this.gridlinesOn = true
        } else {
            // toggle variable
            self.gridlinesOn = !self.gridlinesOn

            // toggle 'active' class at toggle button
            self.toggleButtonEl.forEach(button => (
                button.classList.toggle('fx--active')
            ))

            // toggle 'gridlines' class at document root element
            document.documentElement.classList.toggle('fx--debug-grid')

            // save behavior in local storage
            localStorage.setItem('gridlines', self.gridlinesOn.toString())
        }
    }
}

/**
 *     @section 3. Export class
 */

export default (new DebugGridlines())

// end of debug-gridlines.js
