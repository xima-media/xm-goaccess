/**
 *    Anchor navigation
 *
 *    @tableOfContent
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
    timelineItemWidth: number;
    get scrollWidth(): number {
        return this._scrollWidth;
    }

    set scrollWidth(value: number) {
        this._scrollWidth = value;
    }
    private _scrollWidth: number;
    get containerItemEl(): NodeListOf<HTMLElement> {
        return this._containerItemEl;
    }

    set containerItemEl(value: NodeListOf<HTMLElement>) {
        this._containerItemEl = value;
    }
    private _containerItemEl: NodeListOf<HTMLElement>;
    get containerItemsEl(): HTMLElement {
        return this._containerItemsEl;
    }

    set containerItemsEl(value: HTMLElement) {
        this._containerItemsEl = value;
    }
    private _containerItemsEl: HTMLElement;
    get buttonScrollNextEl(): HTMLButtonElement {
        return this._buttonScrollNextEl;
    }

    set buttonScrollNextEl(value: HTMLButtonElement) {
        this._buttonScrollNextEl = value;
    }
    private _buttonScrollNextEl: HTMLButtonElement;
    get buttonScrollPrevEl(): HTMLButtonElement {
        return this._buttonScrollPrevEl;
    }

    set buttonScrollPrevEl(value: HTMLButtonElement) {
        this._buttonScrollPrevEl = value;
    }
    private _buttonScrollPrevEl: HTMLButtonElement;
    get buttonEl(): NodeListOf<HTMLButtonElement> {
        return this._buttonEl;
    }

    set buttonEl(value: NodeListOf<HTMLButtonElement>) {
        this._buttonEl = value;
    }
    private _buttonEl: NodeListOf<HTMLButtonElement>;
    get container(): HTMLElement {
        return this._container;
    }

    set container(value: HTMLElement) {
        this._container = value;
    }
    private _container: HTMLElement;
    constructor() {
        app.log('component "anchor navigation" loaded')

        // only if element on the page
        if (document.querySelectorAll<HTMLElement>('.navigation--anchor').length) {

            this._container = document.querySelector<HTMLElement>('.navigation--anchor')
            this._buttonEl = this._container.querySelectorAll<HTMLButtonElement>('.navigation__button')
            this._buttonScrollPrevEl = this._container.querySelector<HTMLButtonElement>('.navigation__button.left')
            this._buttonScrollNextEl = this._container.querySelector<HTMLButtonElement>('.navigation__button.right')
            this._containerItemsEl = this._container.querySelector<HTMLElement>('.navigation__items')
            this._containerItemEl = this._container.querySelectorAll<HTMLElement>('.navigation__item')
            this._scrollWidth = 200

            // methods
            this.events()
        }
    }

    /**
     * Events
     */
    events () {
        // variables
        const self = this

        // buttons
        self._buttonEl.forEach((button) => button.addEventListener('click', () => self.scrollToTimelineItem({buttonEl: button})))

        // horizontal scrolling
        self._containerItemEl.forEach((item) => self.activateTimelineItemByScrolling({item: item}))
    }

    /**
     * Scrolling
     */
    scrollToTimelineItem ({buttonEl}: { buttonEl: any }) {
        // variables
        const self = this
        const scrollPositionX = self._containerItemsEl.scrollLeft

        // scroll direction
        if (buttonEl.classList.contains('right')) {
            self.timelineItemWidth = scrollPositionX + self._scrollWidth
        } else if (buttonEl.classList.contains('left')) {
            self.timelineItemWidth = scrollPositionX - self._scrollWidth
        }

        // smooth scrolling
        self._containerItemsEl.scrollTo({
            left: self.timelineItemWidth,
            behavior: 'smooth'
        })
    }

    /**
     * Button show/hide
     */
    activateTimelineItemByScrolling ({item}: { item: any }) {
        // variables
        const self = this

        // observe
        new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // prev button
                    if (entry.target.previousElementSibling) {
                        self._buttonScrollPrevEl.removeAttribute('disabled')
                    } else {
                        self._buttonScrollPrevEl.setAttribute('disabled', 'disabled')
                    }

                    // next button
                    if (entry.target.nextElementSibling) {
                        self._buttonScrollNextEl.removeAttribute('disabled')
                    } else {
                        self._buttonScrollNextEl.setAttribute('disabled', 'disabled')
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
