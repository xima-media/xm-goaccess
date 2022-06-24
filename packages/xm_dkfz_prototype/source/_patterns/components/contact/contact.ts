/**
 *    Contact
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
import './contact.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Contact {
    constructor () {
        app.log('component "contact" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Contact())

// end of contact.js
