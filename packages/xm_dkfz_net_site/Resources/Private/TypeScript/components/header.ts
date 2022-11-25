import app from './basic'

class Header {
  headerEL
  headerStickyTriggerEL

  constructor () {
    this.headerEL = document.querySelector('.header')

    if (this.headerEL) {
      this.headerStickyTriggerEL = this.headerEL.querySelector('.header__sticky-trigger')

      // methods
      this.stickyHeader()
    }
  }

  stickyHeader () {
    const self = this

    // in viewport
    app.inViewport(self.headerStickyTriggerEL, document.documentElement, 'fx--header-not-sticky')
  }
}

export default (new Header())
