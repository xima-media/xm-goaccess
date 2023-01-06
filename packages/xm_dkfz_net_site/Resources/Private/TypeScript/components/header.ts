import app from './basic'

class Header {
  constructor() {
    const headerEL = document.querySelector('.header')
    const headerStickyTriggerEL = document.querySelector('.header .header__sticky-trigger')

    if (headerEL) {
      headerEL.classList.add('is-loaded')
    }

    if (headerEL && headerStickyTriggerEL) {
      app.inViewport(headerStickyTriggerEL, document.documentElement, 'fx--header-not-sticky')
    }
  }
}

export default new Header()
