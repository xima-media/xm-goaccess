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


/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class QuickSearch {
    quickSearchOpenState
    quickSearchEl
    quickSearchInputEl
    quickSearchButtonToggleEl

    constructor () {
        app.log('component "quick search" loaded')
        this.quickSearchOpenState = false
        this.quickSearchEl = document.querySelector<HTMLElement>('.quick-search')

        // main element existing?
        if (this.quickSearchEl) {
            this.quickSearchInputEl = this.quickSearchEl.querySelector<HTMLInputElement>('.field__input')
            this.quickSearchButtonToggleEl = this.quickSearchEl.querySelector<HTMLButtonElement>('.quick-search__button--toggle')

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
        self.quickSearchButtonToggleEl.addEventListener('click', () => self.toggleQuickSearch())

        // outside click
        document.addEventListener('mousedown', event => self.clickOutsideQuickSearch(event))
    }

    /**
     * Open/close quick search
     */
    toggleQuickSearch () {
        const self = this

        // toggle 'open' class
        self.quickSearchEl.classList.toggle('fx--open')

        // toggle state
        self.quickSearchOpenState = !self.quickSearchOpenState

        // focus input element
        if (self.quickSearchOpenState) {
            self.quickSearchInputEl.focus()
        }

        // @todo aria-expanden togglen
    }

    /**
     * click outside and close the quick search
     * @param event MouseEvent
     */
    clickOutsideQuickSearch (event: MouseEvent) {
        const self = this
        const targetParentEl = (<HTMLElement>event.target).closest('.quick-search')

        if (targetParentEl === null && !event.target.classList.contains('autocomplete-suggestion')) {
            if (self.quickSearchEl.classList.contains('fx--open')) {
                self.toggleQuickSearch()
            }
        }

        // @todo funktioniert nicht wenn auf carousel gedrückt wird
    }
}

/**
 *     @section 3. Export class
 */

export default (new QuickSearch())

// end of quick-search.js
