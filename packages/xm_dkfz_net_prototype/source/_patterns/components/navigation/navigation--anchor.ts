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
    private _navigationLinks: NodeListOf<HTMLElement>;
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
                this._containerItemEl = this._containerItemsEl.querySelectorAll<HTMLElement>('.navigation__item')
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

            // active links
            let nav = document.querySelector('.navigation--anchor');
            let navItems = nav.querySelector('.navigation__items');
            let navItem = navItems.querySelectorAll('.navigation__link');
            let sections = document.querySelectorAll('.content-wrapper')

            navItem.forEach(el => {
                el.addEventListener('click', function(){
                    navItem.forEach(nav=> {
                      nav.classList.remove('active')
                      nav.parentElement.classList.remove('active')
                      }
                    );

                    this.classList.add('active')
                    if(this.classList.contains('active')) {
                      this.parentElement.classList.add('active')
                    }
                })
            })

            // change the active class on anchor menu item
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if(entry.isIntersecting) {
                      let entryTarget = entry.target;
                      let entryTargetId = '#' + entryTarget.getAttribute('id')

                        navItem.forEach(li => {
                            li.classList.remove('active')
                            li.parentElement.classList.remove('active')

                            let liHref = li.getAttribute('href')

                            if(liHref === entryTargetId) {
                                li.classList.add('active')
                                li.parentElement.classList.add('active')
                            }
                        })
                    }
                })
            }, {
                threshold: [ 0.5, 0.75, 1]
            })

            sections.forEach(section => {
                observer.observe(section)
            })

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
