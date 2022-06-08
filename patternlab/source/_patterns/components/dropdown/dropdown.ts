/**
 *    Dropdown
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
import './dropdown.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'
import 'bootstrap/js/src/dropdown'

/**
 *     @section 2. Class
 */

class Dropdown {
    constructor () {
        app.log('component "dropdown" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Dropdown())

// end of dropdown.js
