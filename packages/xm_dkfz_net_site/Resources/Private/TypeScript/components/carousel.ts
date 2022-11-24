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
import app from './basic'
import Swiper, { Navigation, Pagination } from 'swiper'


/**
 *     @section 2. Class
 */

class Carousel {
    carouselEl
    constructor () {
        app.log('component "carousel" loaded')
        this.carouselEl = document.querySelectorAll<HTMLElement>('.carousel')

        // methods
        this.init()
    }

    /**
     * Init carousels
     */
    init () {
        const self = this

        // init every carousel
        self.carouselEl.forEach((element) => {
            const cols = JSON.parse(element.dataset.cols)
            const paginationStyle = JSON.parse(element.dataset.paginationStyle)
            const carouselEl = element.querySelector('.swiper') as HTMLElement

            // init
            const swiper = new Swiper(carouselEl, {
                modules: [Navigation, Pagination],
                loop: false,
                speed: 600,
                pagination: {
                    el: element.querySelector('.swiper-pagination') as HTMLElement,
                    clickable: true
                },
                navigation: {
                    nextEl: element.querySelector('.swiper-button-next') as HTMLElement,
                    prevEl: element.querySelector('.swiper-button-prev') as HTMLElement,
                },
                breakpoints: {
                    0: {
                        slidesPerView: parseInt(cols.xs),
                        slidesPerGroup: parseInt(cols.xs),
                        pagination: {
                            type: paginationStyle.xs
                        }
                    },
                    480: {
                        slidesPerView: parseInt(cols.sm),
                        slidesPerGroup: parseInt(cols.sm),
                        pagination: {
                            type: paginationStyle.sm
                        }
                    },
                    768: {
                        slidesPerView: parseInt(cols.md),
                        slidesPerGroup: parseInt(cols.md),
                        pagination: {
                            type: paginationStyle.md
                       }
                    },
                    1024: {
                        slidesPerView: parseInt(cols.lg),
                        slidesPerGroup: parseInt(cols.lg),
                        pagination: {
                             type: paginationStyle.lg
                        }
                    }
                },
                on: {
                    init: (swiper) => {
                        // print items count
                        self.printItemsCount(swiper)
                    },
                },
            })
        })
    }

    /**
     * Print items count as data attribute
     */
    printItemsCount (swiper: Swiper) {
        // print item counts as data attribute
        swiper.$el[0].parentElement.dataset.count = String(swiper.$el[0].querySelectorAll<HTMLElement>('.swiper-slide').length)
    }
}

/**
 *     @section 3. Export class
 */

export default (new Carousel())

// end of carousel.js
