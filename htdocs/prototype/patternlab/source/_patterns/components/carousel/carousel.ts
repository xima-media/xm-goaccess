/**
 *    Carousel
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
import './carousel.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'
import Swiper, { Navigation, Pagination } from 'swiper'


/**
 *     @section 2. Class
 */

class Carousel {
    constructor () {
        app.log('component "carousel" loaded')

        // @todo noch schön schreiben
        document.querySelectorAll<HTMLElement>('.carousel').forEach((element) => {
            const swiper = new Swiper(element.querySelector<HTMLElement>('.swiper'), {
                // configure Swiper to use modules
                modules: [Navigation, Pagination],

                // Optional parameter
                // direction: 'vertical',
                loop: false,
                slidesPerView: 3,
                // spaceBetween: 30,
                // Responsive breakpoints
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 1,
                        // spaceBetween: 20
                    },
                    // when window width is >= 480px
                    480: {
                        slidesPerView: 2,
                        // spaceBetween: 30
                    },
                    // when window width is >= 640px
                    640: {
                        slidesPerView: 3,
                        // spaceBetween: 40
                    },
                    1024: {
                        slidesPerView: parseInt(element.dataset.cols),
                        // spaceBetween: 40
                    }
                },

                // If we need pagination
                pagination: {
                    el: element.querySelector<HTMLElement>('.swiper-pagination'),
                    clickable: true
                },

                // Navigation arrows
                navigation: {
                    nextEl: element.querySelector<HTMLElement>('.swiper-button-next'),
                    prevEl: element.querySelector<HTMLElement>('.swiper-button-prev'),
                },
            })
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Carousel())

// end of carousel.js
