import Swiper, { Navigation, Pagination } from 'swiper'

class Carousel {
  carouselEl
  constructor () {
    this.carouselEl = document.querySelectorAll<HTMLElement>('.carousel')

    // methods
    this.init()
  }

  init () {
    const self = this

    // init every carousel
    self.carouselEl.forEach((element) => {
      const cols = JSON.parse(element.dataset.cols)
      const paginationStyle = JSON.parse(element.dataset.paginationStyle)
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
          prevEl: element.querySelector<HTMLElement>('.swiper-button-prev')
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
          }
        }
      })
    })
  }

  printItemsCount (swiper: Swiper) {
    // print item counts as data attribute
    swiper.$el[0].parentElement.dataset.count = String(swiper.$el[0].querySelectorAll<HTMLElement>('.swiper-slide').length)
  }
}

export default (new Carousel())
