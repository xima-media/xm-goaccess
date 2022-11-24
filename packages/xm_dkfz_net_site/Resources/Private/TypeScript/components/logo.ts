/**
 *    Logo
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
import app from './basic.js'

/**
 *     @section 2. Class
 */

class Logo {
    constructor () {
        app.log('component "logo" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Logo())

// end of logo.js
