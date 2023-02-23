const slider = document.getElementById('terminalSlider')

function onSliderButtonClick(isPrev: boolean): void {
  const current = parseInt(getComputedStyle(slider).getPropertyValue('--current'))
  const count = parseInt(getComputedStyle(slider).getPropertyValue('--count'))
  const next = isPrev ? ((current + count + 1) % count) + 1 : (current + 1) % count

  goSlide(next)
}

function goSlide(nextNumber: number): void {
  slider.classList.add('animation')

  setTimeout(() => {
    slider.style.setProperty('--current', nextNumber.toString())
    slider.classList.remove('animation')
  }, 400)

}

if (slider) {
  document.querySelector('button.prev')?.addEventListener('click', onSliderButtonClick.bind(null, true))
  document.querySelector('button.next')?.addEventListener('click', onSliderButtonClick.bind(null, false))

  setInterval(onSliderButtonClick.bind(null, false), 5000)
}
