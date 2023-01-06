class Topnews {
  protected slider: HTMLDivElement

  protected currentSlideNr = 0

  protected slideCount = 0

  constructor() {
    const slider = document.querySelector<HTMLDivElement>('.topnews')
    const buttons = document.querySelectorAll('.topnews button')
    const bullets = document.querySelectorAll('.topnews__bullets a')

    if (!buttons || !slider || !bullets) {
      return
    }

    this.slider = slider
    this.readSliderState()

    buttons.forEach(btn => {
      btn.addEventListener('click', this.onButtonClick.bind(this))
    })

    bullets.forEach(bullet => {
      bullet.addEventListener('click', this.onBulletClick.bind(this))
    })
  }

  protected readSliderState() {
    this.currentSlideNr = parseInt(getComputedStyle(this.slider).getPropertyValue('--current'))
    this.slideCount = parseInt(getComputedStyle(this.slider).getPropertyValue('--count'))
  }

  protected writeSliderState(currentSlide: number) {
    this.currentSlideNr = currentSlide
    this.slider.style.setProperty('--current', currentSlide.toString())
    this.slider.classList.remove('animation')
  }

  onButtonClick(e: Event) {
    e.preventDefault()
    const button = e.currentTarget as HTMLAnchorElement
    const modifier = button.classList.contains('next') ? 1 : this.slideCount - 1
    const next = (this.currentSlideNr + modifier) % this.slideCount
    this.slider.classList.add('animation')

    setTimeout(() => {
      this.writeSliderState(next)
    }, 400)
  }

  onBulletClick(e: Event) {
    e.preventDefault()
    const bullet = e.currentTarget as HTMLAnchorElement
    const next = parseInt(bullet.getAttribute('data-news') ?? '')

    this.slider.classList.add('animation')

    setTimeout(() => {
      this.writeSliderState(next)
    }, 400)
  }
}

export default new Topnews()
