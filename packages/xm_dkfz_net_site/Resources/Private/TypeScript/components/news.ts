class News {
  constructor() {
    if (document.querySelector('.image-slider')) {
      document.querySelectorAll('.image-slider').forEach(slider => {
        this.initSlider(slider)
      })
    }
  }

  initSlider(slider: Element) {
    slider.querySelector('button.prev')?.addEventListener('click', this.onSliderButtonClick.bind(this, true, slider))
    slider.querySelector('button.next')?.addEventListener('click', this.onSliderButtonClick.bind(this, false, slider))
  }

  onSliderButtonClick(isPrev: boolean, slider: HTMLElement) {
    const current = parseInt(slider.getAttribute('data-current') ?? '')
    const count = slider.querySelectorAll('img').length
    const next = isPrev ? ((current + count + 1) % count) + 1 : (current % count) + 1

    this.goSlide(slider, next, isPrev)
  }

  goSlide(slider: Element, nextNumber: number, isPrev: boolean) {
    const animationClass = isPrev ? 'go-prev' : 'go-next'

    slider.querySelector('.image-slider__item:nth-child(' + nextNumber + ')')?.classList.add(animationClass)
    slider.classList.remove('no-animation')
    slider.classList.add(animationClass)
    slider.setAttribute('data-current', nextNumber.toString())

    setTimeout(() => {
      slider.classList.add('no-animation')
      slider.classList.remove('go-prev', 'go-next')
      slider.querySelector('.current')?.classList.remove('current')
      slider.querySelector('.image-slider__item:nth-child(' + nextNumber + ')')?.classList.remove(animationClass)
      slider.querySelector('.image-slider__item:nth-child(' + nextNumber + ')')?.classList.add('current')
    }, 300)
  }
}

export default new News()
