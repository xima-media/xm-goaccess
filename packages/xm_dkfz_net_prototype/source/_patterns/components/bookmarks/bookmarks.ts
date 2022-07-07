/**
 *    Bookmarks
 *
 *    @tableOfContent
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
import './bookmarks.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */


class Bookmarks {
    constructor () {
        app.log('component "bookmarks" loaded')
        this.init()
    }

    init () {

    }
}

/**
 *     @section 4. Export class
 */

export default (new Bookmarks())

// end of bookmarks.js
