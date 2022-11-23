/**
 *    Tag
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
import './tag.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class Tag {
    constructor () {
        app.log('component "tag" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Tag())

// end of tag.js
