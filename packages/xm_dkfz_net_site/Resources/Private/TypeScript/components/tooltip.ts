/**
 *    Tooltip
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


/** @section 1.2 Import js */
import app from './basic'
import tippy from 'tippy.js'

/**
 *     @section 2. Class
 */

class Tooltip {
    constructor () {
        app.log('component "tooltip" loaded')

        document.querySelectorAll<HTMLElement>('[data-tooltip]').forEach((tooltip) => {
        const placement = tooltip.dataset.tooltipPlacement ? tooltip.dataset.tooltipPlacement : 'bottom'
        const content = tooltip.dataset.tooltip
        let showOnCreate = tooltip.dataset.tooltipShowOnCreate ? tooltip.dataset.tooltipShowOnCreate : false

        // init tooltip
        // @ts-ignore
            tippy(tooltip, {
            interactive: true,
            trigger: 'mouseenter focus',
            placement: placement,
            appendTo: document.body,
            content: content,
            delay: [app.transitionTime, 0],
            touch: false,
            showOnCreate: showOnCreate,
            onShow (instance) {
                if (showOnCreate) {
                    // hide after 3 seconds
                    setTimeout(() => {
                        instance.hide()
                    }, 3000)

                    // set variable to false to stay at hover
                    showOnCreate = false
                }
            }
        })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Tooltip())

// end of tooltip.js
