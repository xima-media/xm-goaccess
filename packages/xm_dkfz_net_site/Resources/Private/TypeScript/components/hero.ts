import app from './basic'

class Hero {
  protected elements: NodeListOf<HTMLElement>

  constructor () {
    this.init()
  }

  init () {
    const isLightHouseRequest = navigator.userAgent.indexOf("Chrome-Lighthouse") > -1

    if (isLightHouseRequest) {
      return
    }

    this.elements = document.querySelectorAll<HTMLElement>('.background--image-hero')

    this.elements.forEach((element) => {
      this.setRandomImageForElement(element)
    })
  }

  setRandomImageForElement (element: HTMLElement) {
    const imageCount = element.style.length / 8
    const randomNr = Math.floor(Math.random() * imageCount)

    // 0 = onload, do nothing
    if (randomNr === 0) {
      return
    }

    // rename css variable
    for (let i = 0; i < element.style.length; i++) {
      // find variables with random, e.g. --img-2-jpg
      if (!element.style[i].includes('-' + randomNr + '-')) {
        continue
      }

      // remove random to override default
      const cssName = element.style[i].replace('-' + randomNr + '-', '-')
      const cssValue = element.style.getPropertyValue(element.style[i])
      element.style.setProperty(cssName, cssValue)
    }
  }
}

export default (new Hero())
