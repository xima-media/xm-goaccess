import app from '../basic/basic'
import './disruptor.scss'

class Disruptor {

    constructor() {
        app.log('component "disruptor" loaded')

        this.bindDisruptorLoad()
    }

    protected bindDisruptorLoad() {
        document.addEventListener('DOMContentLoaded', () => {
            const disruptorModal = Array.from(document.querySelectorAll('.disruptor-wrapper'))

            disruptorModal.forEach(modal => {
                const disruptorModalFixed = modal.nextElementSibling

                if(modal as HTMLElement) {
                    app.lightbox.startLoading()
                    app.lightbox.open(2, 'disruptor')
                    app.lightbox.displayContent(modal.innerHTML)

                    document.addEventListener('lightboxClose', e => {
                        const target = e.target as HTMLButtonElement
                        const root = target.querySelector('html')

                        if (root.dataset.lightBoxType && root.dataset.lightBoxType === 'disruptor' ) {
                            disruptorModalFixed.classList.remove('d-none')
                            modal.classList.add('d-none')
                        }
                      })
                    app.lightbox.stopLoading()
                }
            })
        });
    }
}

export default (new Disruptor())
