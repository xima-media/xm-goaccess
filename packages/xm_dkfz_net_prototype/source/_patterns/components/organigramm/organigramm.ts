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

/**
 *     @section 2. Class
 */

class Organigramm {
    constructor () {
        const self = this

        app.log('component "organigramm" loaded')

        if (document.querySelector<HTMLElement>('.frame-type-bw_static_template')) {
            self.init()
            self.closeAllOverlays()
        }
    }

    closeAllOverlays() {
        document.querySelectorAll('.organigram__detail').forEach(detail => {
            detail.classList.remove('show');
        })
    }

    init() {
        const self = this

        const boxes = document.querySelectorAll('.organigram__boxes');
        const overlayContainer = document.querySelector('.organigram__overlays');

        document.querySelectorAll('.organigram__detail .btn-close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                self.closeAllOverlays()
                overlayContainer.classList.remove('active')
            })
        })

        boxes.forEach(box => {
            box.addEventListener('click', (e) => {

                self.closeAllOverlays()

                const box = e.currentTarget as HTMLElement;
                const boxId = box.getAttribute('data-box-id')

                const overlay = document.querySelector('.organigram__detail[data-box-target-id="'+boxId+'"]')
                overlay.classList.add('show')
                overlayContainer.classList.add('active')
            })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Organigramm())

// end of organigramm.js
