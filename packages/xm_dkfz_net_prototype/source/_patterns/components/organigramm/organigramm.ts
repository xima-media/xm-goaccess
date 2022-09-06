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

        this.event()
    }

    event() {
        const container = document.querySelector<HTMLElement>('.frame-type-bw_static_template');
        const infoBox = container.querySelectorAll<HTMLElement>('.infobox');

        infoBox.forEach(box => {
            box.addEventListener('click', (e) => {
                const boxEl = box.nextElementSibling;
                const boxElAttr = boxEl.getAttribute('aria-labelledby')

                // add distance from modal to footer
                if (boxElAttr === 'Forschungsschwerpunkte') {
                    const footer = document.querySelector<HTMLElement>('footer');
                    footer.style.marginTop = '20' + 'rem';
                }
            })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Organigramm())

// end of organigramm.js
