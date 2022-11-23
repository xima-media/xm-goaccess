/**
 *    Loading
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
import './loading.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class Loading {
    constructor () {
            app.log('component "loading" loaded')
        }
}

/**
 *     @section 3. Export class
 */

export default (new Loading())

// end of loading.js
