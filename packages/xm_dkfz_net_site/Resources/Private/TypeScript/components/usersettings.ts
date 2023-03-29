class Usersettings {
  constructor() {
    this.bindEvents()
  }

  protected bindEvents(): void {
    const switcher = document.querySelector('label[for="switcher"]')
    if (!switcher) {
      return
    }

    // click event
    switcher.addEventListener('click', this.onSwitcherClick.bind(this))

    // onload siwtch
    const listViewSetting = localStorage.getItem('userResultList') ?? ''
    if (listViewSetting === 'list') {
      this.switchView('list')
    }

    // load more button
    const moreButton = document.querySelector('#userResultList-more')
    moreButton.addEventListener('click', this.onLoadMoreClick.bind(this))

    // auto-trigger load more button
    const observer = new IntersectionObserver(this.onInViewMoreButton.bind(this), {
      root: document,
      rootMargin: '0px',
      threshold: 1.0
    })
    observer.observe(moreButton)
  }

  protected onInViewMoreButton(entries): void {
    const [entry] = entries
    if (entry.isIntersecting) {
      entry.target.click()
    }
  }

  protected onSwitcherClick(e: PointerEvent): void {
    e.preventDefault()
    const label = e.currentTarget as HTMLLabelElement
    const toggle = label.querySelector('a')
    const newState = toggle.classList.contains('results__switcher--cards') ? 'list' : 'cards'

    this.switchView(newState)
  }

  protected switchView(state: string): void {
    const toggle = document.querySelector('label[for="switcher"] a')
    toggle.classList.remove('results__switcher--cards', 'results__switcher--list')
    toggle.classList.add('results__switcher--' + state)

    document.querySelector('.list.users').classList.remove('list--list', 'list--cards')
    document.querySelector('.list.users').classList.add('list--' + state)

    document.querySelector('.frame-bwguild_userlist').classList.remove('view-list', 'view-cards')
    document.querySelector('.frame-bwguild_userlist').classList.add('view-' + state)

    localStorage.setItem('userResultList', 'list')
  }

  protected onLoadMoreClick(e: Event): void {
    e.preventDefault()
    const moreButton = e.currentTarget as HTMLLinkElement
    const nextButton = document.querySelector('.list.f3-widget-paginator li.next a')
    const url = nextButton.getAttribute('href')

    moreButton.classList.add('loading')

    void fetch(url).then(async response => {
      // parse response
      const text = await response.text()
      const parser = new DOMParser()
      const html = parser.parseFromString(text, 'text/html')

      // add items
      const items = html.querySelectorAll('.list.users > li')
      items.forEach(item => {
        document.querySelector('.list.users').append(item)
      })

      // add new more link
      const newNextButton = html.querySelector('.list.f3-widget-paginator li.next a')
      if (!newNextButton) {
        moreButton.remove()
      } else {
        nextButton.setAttribute('href', newNextButton.getAttribute('href'))
      }

      // remove loading
      moreButton.classList.remove('loading')
    })
  }
}

const userSettingsObject = new Usersettings()

export default userSettingsObject
