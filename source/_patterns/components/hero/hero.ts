/**
 *    Hero
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
import './hero.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class Hero {
    constructor () {
        app.log('component "hero" loaded')

        this.init();
    }

    init() {
        // max number of images
        const maxImages = 13
        let numRand = Math.floor(Math.random()*maxImages)
        // render the random background image
        if (document.querySelectorAll<HTMLElement>('.background--image-hero').length) {
<<<<<<< HEAD:source/_patterns/components/hero/hero.ts
            document.querySelector<HTMLElement>('.background--image-hero').style.cssText = `background-image:url("../../../Images/examples/hero-startpage-${numRand}.jpg")`
=======
            document.querySelector<HTMLElement>('.background--image-hero').style.cssText = `background-image:url("../../dist/image/examples/hero-startpage-${numRand}.webp")`
>>>>>>> 51d7806c84da219a4b6ff18e43a50da7ac82e82e:htdocs/prototype/patternlab/source/_patterns/components/hero/hero.ts
        }
    }

}

/**
 *     @section 3. Export class
 */

export default (new Hero())

// end of hero.js
