/**
 *    Organigramm
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
import './organigramm.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'
import 'bootstrap/js/src/modal'

/**
 *     @section 2. Class
 */

class Organigramm {
    constructor () {
        app.log('component "organigramm" loaded')

        if (document.querySelector<HTMLElement>('.frame-type-bw_static_template')) {
            this.event()
        }

    }

    event() {
        const container = document.querySelector<HTMLElement>('.frame-type-bw_static_template');
        const infoBox = container.querySelectorAll<HTMLElement>('.infobox');
        const layout = document.querySelector<HTMLElement>('.layout')

        infoBox.forEach(box => {
            box.addEventListener('click', () => {
                setTimeout(() => {
                    const modal = box.nextElementSibling;

                    if(modal.classList.contains('show')) {
                        const modalContent = modal.querySelector<HTMLElement>('.modal-content');
                        const modalContentHeight = modalContent.clientHeight;

                        layout.style.paddingBottom = modalContentHeight + 'px';
                    }
                }, 1000)
            })

            box.nextElementSibling.addEventListener('hidden.bs.modal',  () => {
                layout.style.paddingBottom = 0 + 'px'
            })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Organigramm())

// end of organigramm.js
