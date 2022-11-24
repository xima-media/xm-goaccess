import app from './basic.js'

import {LightboxStyle} from './lightbox.js';

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

                if(modal as HTMLElement && sessionStorage.getItem("disruptor") === null) {
                    app.lightbox.startLoading()
                    app.lightbox.open(LightboxStyle.warning, 'disruptor')
                    app.lightbox.displayContent(modal.innerHTML)

                    document.addEventListener('lightboxClose', e => {
                        const target = e.target as HTMLButtonElement
                        const root = target.querySelector('html')

                        if (root.dataset.lightBoxType && root.dataset.lightBoxType === 'disruptor' ) {
                            disruptorModalFixed.classList.remove('d-none')
                            modal.classList.add('d-none')

                            sessionStorage.setItem('disruptor', 'close')
                        }
                      })
                    app.lightbox.stopLoading()
                }

                if(sessionStorage.getItem("disruptor") !== null) {
                    disruptorModalFixed.classList.remove('d-none')
                    document.querySelector('html').classList.add('modal-disruptor-fixed')
                    modal.classList.add('d-none')
                }
            })
        });
    }
}

export default (new Disruptor())
