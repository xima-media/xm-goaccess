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
        const container = document.querySelector('.navigation--anchor');
        const rightButton = container.querySelector('.right');
        const leftButton = container.querySelector('.left');

        rightButton.addEventListener("click", () => {
            if (container.scrollWidth !== container.scrollLeft) {
                container.scrollLeft += 150;
                leftButton.style.display = 'flex';

                leftButton.addEventListener("click", function () {
                    container.scrollLeft -= 150;

                    if(container.scrollLeft == 0) {
                       leftButton.style.display = 'none';
                    } else {
                        leftButton.style.display = 'flex';
                    }
                });

            } else {
                // @todo button entfernen
                rightButton.style.display = 'none';
            }
        });

        if(container.scrollLeft == 0) {
           leftButton.style.display = 'none';
         }
    }
}

/**
 *     @section 3. Export class
 */

export default (new NavigationAnchor())

// end of navigation--anchor.js
