/**
 *    Hero
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
import './hero.scss'

/** @section 1.2 Import js */
import app from './basic'

/**
 *     @section 2. Class
 */

class Hero {

  protected elements: NodeListOf<HTMLElement>;

  constructor() {
    app.log('component "hero" loaded')

    this.init()
  }

  init() {

    this.elements = document.querySelectorAll<HTMLElement>('.background--image-hero')

    this.elements.forEach((element) => {
      this.setRandomImageForElement(element);
    });
  }

  setRandomImageForElement(element: HTMLElement) {

    const imageCount = element.style.length / 8;
    const randomNr = Math.floor(Math.random() * imageCount);

    // 0 = onload, do nothing
    if (randomNr === 0) {
      return;
    }

    // rename css variable
    for (let i = 0; i < element.style.length; i++) {

      // find variables with random, e.g. --img-2-jpg
      if (element.style[i].indexOf('-' + randomNr + '-') < 0) {
        continue;
      }

      // remove random to override default
      const cssName = element.style[i].replace('-' + randomNr + '-', '-');
      const cssValue = element.style.getPropertyValue(element.style[i]);
      element.style.setProperty(cssName, cssValue);
    }

  }

}

/**
 *     @section 3. Export class
 */

export default (new Hero())

// end of hero.js
