import app from './basic'

class Basic {
  constructor () {
    this.scrollbarWidthAsCssVariable()
    this.detectWebpImageSupport()
  }

  scrollbarWidthAsCssVariable () {
    document.documentElement.style.setProperty('--scrollbar-width', (app.scrollbarWidth) + 'px')
  }

  detectWebpImageSupport () {
    const hasSupport = document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0
    const cssClass = hasSupport ? 'img-webp' : 'no-webp'
    document.documentElement.classList.add(cssClass)
  }
}

export default (new Basic())
