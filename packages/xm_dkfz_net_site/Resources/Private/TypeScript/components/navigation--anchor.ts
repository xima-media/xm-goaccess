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
import app from './basic'

/**
 *     @section 2. Class
 */

class NavigationAnchor {
  public nav: HTMLElement;
  public navItems: HTMLElement;
  public navLinks: Array<any>;

  constructor() {
    app.log('component "anchor navigation" loaded')
    // only if element on the page
    if (document.querySelectorAll<HTMLElement>('.navigation--anchor').length) {
      // methods
      this.cacheDom()
      this.events()
    }
  }

  public cacheDom() {
    this.nav = document.querySelector<HTMLElement>('.navigation--anchor');
    this.navItems = this.nav.querySelector<HTMLElement>('.navigation__items');
    this.navLinks = Array.from(this.navItems.querySelectorAll<HTMLElement>('.navigation__link'));
  }

  /**
   * Events
   */
  protected events () {
    // variables
    const sections = document.querySelectorAll<HTMLElement>('.content-wrapper');
    const self = this;

    const observer = new IntersectionObserver(this.observerCallback, { threshold: 0.1 });

    this.navLinks.forEach(link => {
      link.addEventListener('click', () => {
        sections.forEach((section) => observer.unobserve(section));
        self.scrollStop(() => {
          sections.forEach((section) => observer.observe(section));
        })
      })
    })
    sections.forEach((section) => observer.observe(section));

    this.scrollableNavigation();
  }

  protected observerCallback(entries: any[]) {
    const navListItems = Array.from(document.querySelectorAll<HTMLElement>('.navigation--anchor .navigation__items .navigation__link'))
    entries.forEach((entry) => {
      let sectionId = entry.target.id;
      let currentLink = navListItems.filter(
        (link) => link.getAttribute("href").substr(1) === sectionId
      );
      if(currentLink.length > 0) {
        if (!entry.isIntersecting) {
          currentLink[0].classList.remove("active")
        } else {
          currentLink[0].classList.add("active");
          scrollNavItemIntoView(currentLink[0])
        }
      }
    });

    function scrollNavItemIntoView(activeLink: HTMLElement) {
      if (document.body.clientWidth >= 1800) {
        activeLink.scrollIntoView()
      }
    }
  }

  protected scrollableNavigation() {
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

  protected scrollHorizontallyByClick(scrollWrapper: Element, navButtonRight: Element, navButtonLeft: Element, scrollValue = 200) {
    navButtonRight.addEventListener('click', () => {
      scrollWrapper.scrollLeft += scrollValue;
    })

    navButtonLeft.addEventListener('click', () => {
      scrollWrapper.scrollLeft -= scrollValue;
    })
  }

  protected scrollStop (callback: () => void, refresh = 66) {

    if (!callback || typeof callback !== 'function') return;

    // @ts-ignore
    let isScrolling: NodeJS.Timeout;

    window.addEventListener('scroll', function (event) {

      // Clear our timeout throughout the scroll
      window.clearTimeout(isScrolling);
      isScrolling = setTimeout(callback, refresh);

    }, false);

  }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js

