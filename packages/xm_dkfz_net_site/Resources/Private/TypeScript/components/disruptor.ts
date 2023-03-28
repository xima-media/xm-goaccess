import { LightboxStyle } from './lightbox'
import Lightbox from './lightbox'

class Disruptor {
  protected disruptorLightbox: Lightbox
  protected disruptorModalFixed: Element
  protected disruptorTemplate: Element
  constructor() {
    if (this.cacheDom()) {
      this.bindDisruptorLoad()
    }
  }

  protected cacheDom(): boolean {
    const disruptorModalFixed = document.querySelector('.disruptor-wrapper--fixed')
    const disruptorTemplate = document.querySelector('.disruptor-wrapper')

    if (!disruptorModalFixed || !disruptorTemplate) {
      return false
    }

    this.disruptorModalFixed = disruptorModalFixed
    this.disruptorTemplate = disruptorTemplate

    return true
  }

  protected bindDisruptorLoad(): void {
    if (sessionStorage.getItem('disruptor') === null) {
      this.showDisruptorLightbox()
    }

    if (sessionStorage.getItem('disruptor') !== null) {
      this.disruptorModalFixed?.classList.remove('d-none')
      document.querySelector('html')?.classList.add('modal-disruptor-fixed')
    }
  }

  protected showDisruptorLightbox(): void {
    this.disruptorLightbox = new Lightbox()
    this.disruptorLightbox.startLoading()
    this.disruptorLightbox.open(LightboxStyle.warning)
    this.disruptorLightbox.displayContent(this.disruptorTemplate.innerHTML)
    this.disruptorLightbox.stopLoading()

    this.bindDisruptorCloseEvent()
  }

  protected bindDisruptorCloseEvent(): void {
    this.disruptorLightbox.box.addEventListener('lightbox:close', () => {
      this.disruptorModalFixed?.classList.remove('d-none')

      sessionStorage.setItem('disruptor', 'close')
    })
  }
}

export default new Disruptor()
