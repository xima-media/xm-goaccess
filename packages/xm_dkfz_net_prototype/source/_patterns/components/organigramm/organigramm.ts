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
        app.log('component "organigramm" loaded')

        this.init()
    }

    init() {
        const infoboxArray = document.querySelectorAll<HTMLElement>('.infobox')

        infoboxArray.forEach(el => {
            el.addEventListener('click', (event:Event) => {
                const target = event.target as HTMLElement;
                const elChildren = target.nextElementSibling;

                if(elChildren.classList.contains('d-none')) {
                    elChildren.classList.remove('d-none');
                }

                const elParent = el.parentElement.parentElement.parentElement;
                const info = Array.from(elParent.querySelectorAll('.infobox') as NodeListOf<HTMLElement>);
                const closeBtn = elChildren.querySelector<HTMLButtonElement>('.modal-detail--close');
                const modalHeight = elChildren.clientHeight - 90;
                const footer = document.querySelector<HTMLElement>('.footer');

                info.forEach(element => {
                    element.classList.add('hide');
                    target.parentElement.style.zIndex = '2';
                    target.classList.add('d-none');

                    if(!elChildren.classList.contains('.d-none')) {
                        footer.style.marginTop = modalHeight + 'px';
                    }

                    closeBtn.addEventListener('click', () => {
                        element.classList.remove('hide');
                        target.parentElement.style.zIndex = '0';
                        target.parentElement.style.removeProperty('zIndex');
                        target.classList.remove('d-none');
                        elChildren.classList.add('d-none');
                        footer.style.marginTop = '';
                    })
                })
            })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Organigramm())

// end of organigramm.js
