/**
 *    Accordion
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
import './accordion.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'
import 'bootstrap/js/src/collapse'

/**
 *     @section 2. Class
 */

class Accordion {
    constructor () {
        app.log('component "accordion" loaded')

//         this.toggleAccordionItem()
    }

//     toggleAccordionItem () {
//         const acc = document.querySelectorAll('.accordion__btn')
//         let i
//
//         for (i = 0; i < acc.length; i++) {
//             acc[i].addEventListener('click', function (e) {
//                 const self = this
//                 const panel = this.nextElementSibling
//                 const wrapper = findAncestor(self, '.accordion__items')
//                 const accordionItems = wrapper.querySelectorAll('.accordion__item')
//                 const ariaExpanded = self.getAttribute('aria-expanded')
//
//                 if (ariaExpanded === 'true') {
//                     self.setAttribute('aria-expanded', false)
//                 } else if (ariaExpanded === 'false') {
//                     self.setAttribute('aria-expanded', true)
//                 }
//
//                 self.classList.toggle('active')
//                 panel.style.maxHeight ? panel.style.maxHeight = null : panel.style.maxHeight = panel.scrollHeight + 'px'
//             })
//         }
//     }
}

// @todo warum wurde nicht das bootstrap accordion benutzt?
// @todo auslagern in app
// function findAncestor (el, sel) {
//     while ((el = el.parentElement) && !((el.matches || el.matchesSelector).call(el, sel)));
//     return el
// }

/**
 *     @section 3. Export class
 */

export default (new Accordion())

// end of accordion.js
