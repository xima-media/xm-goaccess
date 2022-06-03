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

        this.initCarousels()
    }

    /**
     * init carousels
     */
    initCarousels () {
        document.querySelectorAll<HTMLElement>('.carousel').forEach((element) => {
            const cols = JSON.parse(element.dataset.cols)
            const swiper = new Swiper(element.querySelector<HTMLElement>('.swiper'), {
                modules: [Navigation, Pagination],
                loop: false,
                slidesPerView: 3,
                pagination: {
                    el: element.querySelector<HTMLElement>('.swiper-pagination'),
                    clickable: true
                },
                navigation: {
                    nextEl: element.querySelector<HTMLElement>('.swiper-button-next'),
                    prevEl: element.querySelector<HTMLElement>('.swiper-button-prev'),
                },
                breakpoints: {
                    320: {
                        slidesPerView: cols.xs,
                        pagination: {
                            type: 'fraction'
                        }
                    },
                    480: {
                        slidesPerView: cols.sm
                    },
                    768: {
                        slidesPerView: cols.md
                    },
                    1024: {
                        slidesPerView: cols.lg
                    }
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
