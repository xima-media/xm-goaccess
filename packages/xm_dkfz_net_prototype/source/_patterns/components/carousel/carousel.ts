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
            const paginationColored = JSON.parse(element.dataset.paginationColored)
            const carouselEl = element.querySelector<HTMLElement>('.swiper')

            // init
            const swiper = new Swiper(carouselEl, {
                modules: [Navigation, Pagination],
                loop: false,
                speed: 600,
                pagination: {
                    el: element.querySelector<HTMLElement>('.swiper-pagination'),
                    clickable: true
                },
                navigation: {
                    nextEl: element.querySelector<HTMLElement>('.swiper-button-next'),
                    prevEl: element.querySelector<HTMLElement>('.swiper-button-prev'),
                },
                breakpoints: {
                    0: {
                        slidesPerView: cols.xs,
                        slidesPerGroup: cols.xs,
                        pagination: {
                            type: paginationStyle.xs
                        }
                    },
                    480: {
                        slidesPerView: cols.sm,
                        slidesPerGroup: cols.sm,
                        pagination: {
                            type: paginationStyle.sm
                        }
                    },
                    768: {
                        slidesPerView: cols.md,
                        slidesPerGroup: cols.md,
                        pagination: {
                            type: paginationStyle.md
                       }
                    },
                    1024: {
                        slidesPerView: cols.lg,
                        slidesPerGroup: cols.lg,
                        pagination: {
                             type: paginationStyle.lg
                        }
                    }
                },
                on: {
                    init: (swiper) => {
                        // print items count
                        self.printItemsCount(swiper)

                        // colored pagination?
                        if (paginationColored) {
                            self.coloredPagination(swiper)
                        }
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

    /**
     * Colored pagination
     */
    coloredPagination (swiper: Swiper) {
        swiper.$el[0].querySelectorAll<HTMLElement>('.swiper-slide').forEach((carouselItemEl, index) => {
            const paginationItemEl = swiper.pagination.el.querySelectorAll('.swiper-pagination-bullet')[index]
            const paginationColor = carouselItemEl.querySelector<HTMLElement>('[data-pattern-color]').dataset.patternColor

            // set color
            paginationItemEl.classList.add('color--' + paginationColor)
        })
    }
}

/**
 *     @section 3. Export class
 */

export default (new Carousel())

// end of carousel.js
