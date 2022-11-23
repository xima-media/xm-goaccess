/**
 *    Employee welcome message
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
import './employee-welcome-message.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class EmployeeWelcomeMessage {
    constructor () {
        app.log('component "employee welcome message" loaded')
    }
}

/**
 *     @section 3. Export class
 */

export default (new EmployeeWelcomeMessage())

// end of employee-welcome-message.js
