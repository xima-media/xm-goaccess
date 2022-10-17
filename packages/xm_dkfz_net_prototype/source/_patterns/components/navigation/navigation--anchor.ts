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
    const sections = document.querySelectorAll<HTMLElement>('.content-wrapper');
    const self = this;

    const observer = new IntersectionObserver(this.observerCallback, { threshold: 0.1 });
    sections.forEach((section) => observer.observe(section));


    /**
     * menu laptop
     */
    window.addEventListener('resize', () => {
      self.navItemsResize()
    })
    if (document.body.clientWidth <= 1800) {
      self.navItemsResize()
    }
  }

  observerCallback(entries: any[]) {
    entries.forEach((entry) => {
      let sectionId = entry.target.id;
      const nav = document.querySelector<HTMLElement>('.navigation--anchor');
      const navItems = nav.querySelector<HTMLElement>('.navigation__items');
      const navLinks = Array.from(navItems.querySelectorAll<HTMLElement>('.navigation__link'));
      let currentLink = navLinks.filter(
        (link) => link.getAttribute("href").substr(1) === sectionId
      );
      if (!entry.isIntersecting) {
        currentLink[0].classList.remove("active");
      } else {
        currentLink[0].classList.add("active");
      }
    });
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
  }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js

