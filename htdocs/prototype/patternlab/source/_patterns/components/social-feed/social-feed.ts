/**
 *    Social feed
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
import './social-feed.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class SocialFeed {
    copyrightButtonEL

    constructor () {
        app.log('component "social-feed" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new SocialFeed())

// end of social-feed.js
