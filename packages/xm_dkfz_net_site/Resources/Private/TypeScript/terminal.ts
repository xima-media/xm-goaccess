const slider: HTMLElement = document.getElementById('terminalSlider')!
const progressBar: HTMLElement = document.getElementById('terminal-progress-bar')!

function onSliderButtonClick(isPrev: boolean): void {
  const current = parseInt(getComputedStyle(slider).getPropertyValue('--current'))
  const count = parseInt(getComputedStyle(slider).getPropertyValue('--count'))
  const next = isPrev ? ((current + count + 1) % count) + 1 : (current + 1) % count

  goSlide(next)
}

function goSlide(nextNumber: number): void {
  slider.classList.add('animation')
  progressBar.classList.remove('run')

  setTimeout(() => {
    slider.style.setProperty('--current', nextNumber.toString())
    slider.classList.remove('animation')
    progressBar.classList.add('run')
  }, 400)
}

if (slider) {
  document.querySelector('button.prev')?.addEventListener('click', onSliderButtonClick.bind(null, true))
  document.querySelector('button.next')?.addEventListener('click', onSliderButtonClick.bind(null, false))

  const seconds = document.getElementById('slider-duration').style.getPropertyValue('--timer')
  const duration = parseInt(seconds.replace('s', ''))

  setInterval(onSliderButtonClick.bind(null, false), duration * 1000)

  const reloadCount = parseInt(document.getElementById('slider-duration').getAttribute('data-slider-reload-runs'))
  if (reloadCount) {
    const count = parseInt(getComputedStyle(slider).getPropertyValue('--count'))
    const meta = document.createElement('meta')
    meta.httpEquiv = 'refresh'
    meta.content = (duration * count * reloadCount + count * 0.4 - 0.4).toString()
    document.getElementsByTagName('head')[0].appendChild(meta)
  }
}
