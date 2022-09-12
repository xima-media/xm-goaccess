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
    constructor() {
        app.log('component "anchor navigation" loaded')

        // only if element on the page
        if (document.querySelectorAll<HTMLElement>('.navigation--anchor').length) {
            // methods
            this.events()
        }
    }

    /**
     * Events
     */
    events () {
        // variables
        const nav = document.querySelector<HTMLElement>('.navigation--anchor');
        const navItems = nav.querySelector<HTMLElement>('.navigation__items');
        const navItem = navItems.querySelectorAll<HTMLElement>('.navigation__link');
        const sections = document.querySelectorAll<HTMLElement>('.content-wrapper');

        /**
         * menu desktop
         */
        // active links
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
                        let liHref = li.getAttribute('href')

                        li.classList.remove('active')
                        li.parentElement.classList.remove('active')

                        if(liHref === entryTargetId) {
                            li.classList.add('active')
                            li.parentElement.classList.add('active')
                        }
                    })
                }
            })
        }, {
            threshold: 0.75
        })

        // filter the section for observe
        Array.from(sections).filter(section => {
            const sectionId = '#' + section.getAttribute('id')

            navItem.forEach(li => {
                let liHref = li.getAttribute('href')

                if(liHref === sectionId) {
                    observer.observe(section)
                }
            })
        })


        /**
         * menu laptop
         */

        const self = this;
        window.addEventListener('resize', () => {
            self.navItemsResize()
        })
        if (document.body.clientWidth <= 1800) {
            self.navItemsResize()
        }
    }

    navItemsResize() {
        const nav = document.querySelector<HTMLElement>('.navigation--anchor');
        const navItems = nav.querySelector<HTMLElement>('.navigation__items');
        const hScroll = nav.querySelector<HTMLElement>('.horizontal-scroll');
        const btnScrollLeft = hScroll.querySelector<HTMLButtonElement>('.navigation__button.left');
        const btnScrollRight = hScroll.querySelector<HTMLButtonElement>('.navigation__button.right');
        let maxScroll = -hScroll.scrollWidth + navItems.offsetWidth;
        let currentScrollPosition = 0;
        let scrollAmount = hScroll.offsetWidth / 4;

        // Button show/hide
        if(hScroll.scrollWidth === hScroll.offsetWidth) {
            btnScrollRight.classList.remove('active')
            navItems.style.justifyContent = 'center'
        } else {
            btnScrollRight.classList.add('active')
            navItems.style.justifyContent = 'flex-start'
        }

        function scrollHorizontally(val: number) {
            currentScrollPosition += (val * scrollAmount);

            if (currentScrollPosition >= 0) {
                currentScrollPosition = 0;
                btnScrollLeft.classList.remove('active')
            } else {
                btnScrollLeft.classList.add('active')
            }

            if (currentScrollPosition <= maxScroll) {
                currentScrollPosition = maxScroll;
                btnScrollRight.classList.remove('active')
            } else {
                btnScrollRight.classList.add('active')
            }

            navItems.style.left = currentScrollPosition + 'px';
        }

        btnScrollLeft.addEventListener('click', () => scrollHorizontally(1))
        btnScrollRight.addEventListener('click', () => scrollHorizontally(-1))

        // sticky menu
        // window.addEventListener('scroll', () => {
        //     const sticky = nav.offsetTop;
        //
        //     if (window.pageYOffset >= sticky) {
        //         nav.classList.add("sticky")
        //     } else {
        //         nav.classList.remove("sticky");
        //     }
        // })
    }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js
