/**
 *    Anchor navigation
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
import './navigation--anchor.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class NavigationAnchor {
    constructor() {
        app.log('component "anchor navigation" loaded')

        const navigationLink = document.querySelectorAll('.navigation--anchor__item');

        navigationLink.forEach(link => {
            link.addEventListener('click', () => {
            link.classList.toggle("current")
            })
        })


        // if size laptop
        const rightButtons = Array.from(document.getElementsByClassName('right'));
        const containers = document.querySelectorAll('.navigation--anchor');

        let index = 0;
        for (const rightButton of rightButtons) {
            const container = containers[index];
            rightButton.addEventListener("click", function () {
                container.scrollLeft += 150;
            });
            index++;
        }




    }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js
