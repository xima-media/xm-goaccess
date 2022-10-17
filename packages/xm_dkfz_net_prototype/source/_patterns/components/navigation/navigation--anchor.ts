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

    const observer = new IntersectionObserver(this.observerCallback, { threshold: 0.1 });
    sections.forEach((section) => observer.observe(section));

    this.scrollableNavigation();
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

  scrollableNavigation() {
    const horizontalScrollItemsWrapper = document.querySelector('.horizontal-scroll .navigation__items');
    const navButtonRight = document.querySelector('.horizontal-scroll .navigation__button.right')
    const navButtonLeft = document.querySelector('.horizontal-scroll .navigation__button.left')

    horizontalScrollItemsWrapper.addEventListener('scroll', (e) => {
      let scroll = horizontalScrollItemsWrapper.scrollLeft

      if(scroll + horizontalScrollItemsWrapper.getBoundingClientRect().width >= horizontalScrollItemsWrapper.scrollWidth) {
        navButtonRight.classList.remove('active')
      } else {
        navButtonRight.classList.add('active')
      }

      if(scroll === 0) {
        navButtonLeft.classList.remove('active')
      } else {
        navButtonLeft.classList.add('active')
      }
    })

    this.scrollHorizontallyByClick(horizontalScrollItemsWrapper, navButtonRight, navButtonLeft)
  }

  scrollHorizontallyByClick(scrollWrapper: Element, navButtonRight: Element, navButtonLeft: Element, scrollValue = 200) {
    navButtonRight.addEventListener('click', () => {
      scrollWrapper.scrollLeft += scrollValue;
    })

    navButtonLeft.addEventListener('click', () => {
      scrollWrapper.scrollLeft -= scrollValue;
    })
  }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js

