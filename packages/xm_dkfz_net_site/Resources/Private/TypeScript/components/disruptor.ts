import app from './basic'

import { LightboxStyle } from './lightbox'
import Lightbox from './lightbox'

class Disruptor {
  protected disruptorLightbox: Lightbox
  constructor() {
    this.bindDisruptorLoad()
  }

  protected bindDisruptorLoad() {
    document.addEventListener('DOMContentLoaded', () => {
      const disruptorModal = Array.from(document.querySelectorAll('.disruptor-wrapper'))

      disruptorModal.forEach(modal => {
        const disruptorModalFixed = modal.nextElementSibling

        if ((modal as HTMLElement) && sessionStorage.getItem('disruptor') === null) {
          this.disruptorLightbox = new Lightbox()
          this.disruptorLightbox.startLoading()
          this.disruptorLightbox.open(LightboxStyle.warning, 'disruptor')
          this.disruptorLightbox.displayContent(modal.innerHTML)

          document.addEventListener('lightboxClose', e => {
            const target = e.target as HTMLButtonElement
            const root = target.querySelector('html')

            if (root && root.dataset.lightBoxType && root.dataset.lightBoxType === 'disruptor') {
              disruptorModalFixed?.classList.remove('d-none')
              modal.classList.add('d-none')

              sessionStorage.setItem('disruptor', 'close')
            }
          })
          this.disruptorLightbox.stopLoading()
        }

        if (sessionStorage.getItem('disruptor') !== null) {
          disruptorModalFixed?.classList.remove('d-none')
          document.querySelector('html')?.classList.add('modal-disruptor-fixed')
          modal.classList.add('d-none')
        }
      })
    })
  }
}

export default new Disruptor()
