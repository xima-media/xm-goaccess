/**
 *    Anchor navigation
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
import './navigation--anchor.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class NavigationAnchor {
    constructor() {
        app.log('component "anchor navigation" loaded')

        if (document.querySelectorAll('.navigation__button').length) {
            this.container = document.querySelector('.navigation__button')
        }
        this.buttonEl = this.container.querySelectorAll('.navigation__button')
        this.buttonScrollPrevEl = this.container.querySelector('.navigation__button.left')
        this.buttonScrollNextEl = this.container.querySelector('.navigation__button.right')
        this.containerItemsEl = this.container.querySelector('.navigation__items')
        this.containerItemEl = this.container.querySelectorAll('.navigation__item')
        this.scrollWidth = 200

        // methods
        this.events()
    }

    /**
     * Events
     */
    events () {
        // variables
        const self = this

        // buttons
        self.buttonEl.forEach((button) => button.addEventListener('click', () => self.scrollToTimelineItem(button)))

        // horizontal scrolling
        self.containerItemEl.forEach((item) => self.activateTimelineItemByScrolling(item))
    }

    /**
     * Scrolling
     */
    scrollToTimelineItem (buttonEl) {
        // variables
        const self = this
        const scrollPositionX = self.containerItemsEl.scrollLeft

        // scroll direction
        if (buttonEl.classList.contains('right')) {
            self.timelineItemWidth = scrollPositionX + self.scrollWidth
        } else if (buttonEl.classList.contains('left')) {
            self.timelineItemWidth = scrollPositionX - self.scrollWidth
        }

        // smooth scrolling
        self.containerItemsEl.scrollTo({
            left: self.timelineItemWidth,
            behavior: 'smooth'
        })
    }

    /**
     * Button show/hide
     */
    activateTimelineItemByScrolling (item) {
        // variables
        const self = this

        // observe
        new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // prev button
                    if (entry.target.previousElementSibling) {
                        self.buttonScrollPrevEl.removeAttribute('disabled')
                    } else {
                        self.buttonScrollPrevEl.setAttribute('disabled', 'disabled')
                    }

                    // next button
                    if (entry.target.nextElementSibling) {
                        self.buttonScrollNextEl.removeAttribute('disabled')
                    } else {
                        self.buttonScrollNextEl.setAttribute('disabled', 'disabled')
                    }
                }
            })
        }, {
            root: document,
            rootMargin: '0px 0px 0px 0px',
            threshold: 1
        }).observe(item)
    }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js
