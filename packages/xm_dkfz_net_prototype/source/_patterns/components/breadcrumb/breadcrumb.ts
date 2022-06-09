/**
 *    Breadcrumb
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
import './breadcrumb.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Breadcrumb {
    breadcrumbLinkEl

    constructor () {
        app.log('component "breadcrumb" loaded')
        this.breadcrumbLinkEl = document.querySelector<HTMLElement>('.breadcrumb__link')

        if (this.breadcrumbLinkEl) {
            this.currentItem()
        }
    }

    /**
     * mark current icon
     */
    currentItem () {
        const self = this

        // @todo Yulia, kann das nicht direkt im HTML gesetzt werden?
        self.breadcrumbLinkEl.setAttribute('aria-current', 'location')
    }
}

/**
 *     @section 3. Export class
 */

export default (new Breadcrumb())

// end of breadcrumb.js
