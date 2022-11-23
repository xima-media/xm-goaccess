/**
 *    Basic
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
// nothing yet

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Basic {
    constructor () {
        app.log('component "basic" loaded')

        this.scrollbarWidthAsCssVariable();
        this.detectWebpImageSupport();
    }

    /**
     * Scrollbar width as css variable
     */
    scrollbarWidthAsCssVariable () {
        document.documentElement.style.setProperty('--scrollbar-width', (app.scrollbarWidth) + "px");
    }

    detectWebpImageSupport () {
        const hasSupport = document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0;
        const cssClass = hasSupport ? 'img-webp' : 'no-webp';
        document.documentElement.classList.add(cssClass);
    }
}

/**
 *     @section 3. Export class
 */

export default (new Basic())

// end of basic.js
