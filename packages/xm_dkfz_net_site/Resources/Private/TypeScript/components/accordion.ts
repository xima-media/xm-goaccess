/**
 *    Accordion
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
import 'bootstrap/js/src/collapse'

/**
 *     @section 2. Class
 */

class Accordion {
    constructor () {
        app.log('component "accordion" loaded')
    }
}


/**
 *     @section 3. Export class
 */

export default (new Accordion())

// end of accordion.js
