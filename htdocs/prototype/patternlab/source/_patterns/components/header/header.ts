/**
 *    Header
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
import './header.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Header {
    headerEL
    headerStickyTriggerEL

    constructor () {
        app.log('component "header" loaded')
        this.headerEL = document.querySelector<HTMLElement>('.header')
        this.headerStickyTriggerEL = this.headerEL.querySelector<HTMLElement>('.header__sticky-trigger')

        // methods
        this.stickyHeader()
    }

    /**
     * Sticky header
     */
    stickyHeader () {
        const self = this

        // in viewport
        app.inViewport(self.headerStickyTriggerEL, document.documentElement, 'fx--header-not-sticky')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Header())

// end of header.js
