/**
 *    Basic
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
// nothing yet

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Basic {
    constructor () {
        app.log('component "basic" loaded')
        this.scrollbarWidthAsCssVariable();
    }

    /**
     * Scrollbar width as css variable
     */
    scrollbarWidthAsCssVariable () {
        document.documentElement.style.setProperty('--scrollbar-width', (app.scrollbarWidth) + "px");
    }
}

/**
 *     @section 3. Export class
 */

export default (new Basic())

// end of basic.js
