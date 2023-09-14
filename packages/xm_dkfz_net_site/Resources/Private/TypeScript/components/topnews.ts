class Topnews {
  protected slider: HTMLDivElement

  protected currentSlideNr = 0

  protected slideCount = 0

  protected newsTimer: number = 0

  protected timerDuration: number = 0

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

    if (this.timerDuration) {
      this.startNewsTimer()
      document.querySelectorAll('.topnews__timer a').forEach(btn => {
        btn.addEventListener('click', this.onPauseButtonClick.bind(this))
      })
    } else {
      document.querySelectorAll('.topnews__timer').forEach(div => {
        div.remove()
      })
    }
  }

  protected startNewsTimer(): void {
    this.newsTimer = setInterval((): void => {
      const nextBtn = document.querySelector('.topnews button.next') as HTMLButtonElement
      nextBtn.click()
      this.resumeTimer()
    }, this.timerDuration * 1000)
  }

  protected readSliderState(): void {
    this.currentSlideNr = parseInt(getComputedStyle(this.slider).getPropertyValue('--current'))
    this.slideCount = parseInt(getComputedStyle(this.slider).getPropertyValue('--count'))
    this.timerDuration = parseInt(getComputedStyle(document.querySelector('main')).getPropertyValue('--timer').replace('s', ''))
  }

  protected writeSliderState(currentSlide: number): void {
    this.currentSlideNr = currentSlide
    this.slider.style.setProperty('--current', currentSlide.toString())
    this.slider.classList.remove('animation')
  }

  protected onPauseButtonClick(e: Event): void {
    e.preventDefault()
    const button = e.currentTarget as HTMLAnchorElement
    if (button.parentElement.classList.contains('topnews__timer--playing')) {
      this.stopTimer()
    } else {
      this.resumeTimer()
    }
  }

  protected resumeTimer(): void {
    const timerDivs = document.querySelectorAll('.topnews__timer')
    timerDivs.forEach(div => {
      div.classList.add('topnews__timer--playing')
    })
    this.startNewsTimer()
  }

  protected stopTimer(): void {
    const timerDivs = document.querySelectorAll('.topnews__timer')
    timerDivs.forEach(div => {
      div.classList.remove('topnews__timer--playing')
    })
    clearInterval(this.newsTimer)
  }

  protected onButtonClick(e: Event): void {
    e.preventDefault()
    const button = e.currentTarget as HTMLAnchorElement
    const modifier = button.classList.contains('next') ? 1 : this.slideCount - 1
    const next = (this.currentSlideNr + modifier) % this.slideCount
    this.slider.classList.add('animation')

    setTimeout(() => {
      this.writeSliderState(next)
    }, 400)

    this.stopTimer()
  }

  protected onBulletClick(e: Event): void {
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
