/**
 *    Tabs
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
import './tabs.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'
import 'bootstrap/js/src/tab'

/**
 *     @section 2. Class
 */

class Tabs {
    constructor () {
        app.log('component "tabs" loaded')

        // @todo warnungen/fehler beheben
        const triggerTabList = [].slice.call(document.querySelectorAll('#tab a'))
        triggerTabList.forEach(function (triggerEl) {
          const tabTrigger = new bootstrap.Tab(triggerEl)

          triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
          })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Tabs())

// end of tabs.js
