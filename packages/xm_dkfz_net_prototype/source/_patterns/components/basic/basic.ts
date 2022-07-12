/**
 *    App
 *
 *    @tableofcontent
 *      1. Dependencies
 *      2. Class
 *      3. Export class
 *
 */

/**
 *     @section 1. Dependencies
 */

// import css dependencies
import './basic.scss'

/**
 *     @section 2. Class
 */

export default {
  /**
   * Variables
   */
  debug: false,
  lang: document.documentElement.lang,
  prototype: document.body.classList.contains('prototype'),
  // transitionTime: 300, // @todo aus CSS auslesen
  transitionTime: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--transition-time')),
  scrollbarWidth: window.innerWidth - document.documentElement.clientWidth,
  color: {
    white: getComputedStyle(document.documentElement).getPropertyValue('--color-white'),
    black: getComputedStyle(document.documentElement).getPropertyValue('--color-black'),
    primary: getComputedStyle(document.documentElement).getPropertyValue('--color-primary'),
    secondary: getComputedStyle(document.documentElement).getPropertyValue('--color-secondary')
  },

  /**
   * get hex from emoji
   * @param emoji string
   */
  emoji(emoji: string) {
    const hex: string = emoji.codePointAt(0).toString(16)
    // @todo ts-ignore weg bekommen
    // @ts-ignore
    const emo: string = String.fromCodePoint('0x' + hex)
    return emo
  },

  /**
   * Print log information if debug is true
   * @param message string
   * @param emoji string
   * @param hexColor string
   */
  log(message: string, emoji = '🔥', hexColor = '#bada55') {
    if (this.debug) {
      // @todo ausgabe von arrays und objekten ermöglichen
      console.log('%c' + this.emoji(emoji) + ' ' + message, 'color: ' + hexColor)
    }
  },

  /**
   * Get url parameters
   * @param url string
   * @param parameter string
   * @return value string
   */
  getUrlParamater(url: string, parameter: string) {
    const urlSearchParams = new URLSearchParams(url)
    const params = Object.fromEntries(urlSearchParams.entries())
    const value = params[parameter]

    return value
  },

  /**
   * Check if an element is in viewport
   * @param checkEl Element
   * @param targetForCssClassEl Element
   * @param cssClass string
   * @param once boolean
   */
  inViewport(checkEl: Element, targetForCssClassEl: Element = checkEl, cssClass: string = 'fx--visible', once: boolean = false) {
    const app = this

    // observe
    new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          app.log('visible')

          if (targetForCssClassEl === checkEl) {
            entry.target.classList.add(cssClass)
          } else {
            targetForCssClassEl.classList.add(cssClass)
          }

          // create custom: reset timeout
          const event = new Event('viewport:in', {bubbles: true})
          entry.target.dispatchEvent(event)
        } else {
          app.log('not visible')

          if (!once) {
            if (targetForCssClassEl === checkEl) {
              entry.target.classList.remove(cssClass)
            } else {
              targetForCssClassEl.classList.remove(cssClass)
            }
          }

          // create custom: reset timeout
          const event = new Event('viewport:out', {bubbles: true})
          entry.target.dispatchEvent(event)
        }
      })
    }, {
      // root: document,
      rootMargin: '0px 0px 0px 0px',
      threshold: 0
    }).observe(checkEl)
  },

}

// end of basic.js
