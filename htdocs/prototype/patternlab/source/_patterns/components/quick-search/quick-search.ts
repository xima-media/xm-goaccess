/**
 *    Quick search
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
import './quick-search.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class QuickSearch {
    quickSearchEl
    quickSearchButtonEl

    constructor () {
        app.log('component "quick search" loaded')
        this.quickSearchEl = document.querySelector<HTMLElement>('.quick-search')
        this.quickSearchButtonEl = this.quickSearchEl.querySelector<HTMLButtonElement>('.quick-search__button')

        if (this.quickSearchButtonEl) {
            // methods
            this.events()
        }
    }

    /**
     * Events
     */
    events () {
        const self = this

        // toggle: quick search
        self.quickSearchButtonEl.addEventListener('click', () => self.toggleQuickSearch())
    }

    /**
     * Open/close quick search
     */
    toggleQuickSearch () {
        const self = this

        // toggle 'open' class
        self.quickSearchEl.classList.toggle('fx--open')
        // @todo aria-expanden togglen
    }
}

/**
 *     @section 3. Export class
 */

export default (new QuickSearch())

// end of quick-search.js
