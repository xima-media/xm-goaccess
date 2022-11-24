/**
 *    Table
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
import './table.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class Table {
    constructor () {
        app.log('component "table" loaded')

    }
}

/**
 *     @section 3. Export class
 */

export default (new Table())

// end of table.js
