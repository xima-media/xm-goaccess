/**
 *    Image
 *
 *    @tableofcontent
 *      1. Dependencies
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

class Image {
    constructor () {
        app.log('component "image" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Image())

// end of image.js
