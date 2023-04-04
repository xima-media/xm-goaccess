class Usersettings {
  constructor() {
    this.bindEvents()
  }

  protected bindEvents(): void {
    const switcher = document.querySelectorAll('a.results__switcher__item')
    if (!switcher.length) {
      return
    }

    // click event
    switcher.forEach(link => link.addEventListener('click', this.onSwitcherClick.bind(this, link)))

    // onload siwtch
    const listViewSetting = localStorage.getItem('userResultList') ?? ''
    if (listViewSetting === 'list') {
      this.switchView('list')
    }

    // load more button
    const moreButton = document.querySelector('#userResultList-more')
    if (moreButton) {
      moreButton?.addEventListener('click', this.onLoadMoreClick.bind(this))

      // auto-trigger load more button
      const observer = new IntersectionObserver(this.onInViewMoreButton.bind(this), {
        root: document,
        rootMargin: '0px',
        threshold: 1.0
      })
      observer.observe(moreButton)
    }
  }

  protected onInViewMoreButton(entries): void {
    const [entry] = entries
    if (entry.isIntersecting) {
      entry.target.click()
    }
  }

  protected onSwitcherClick(link: HTMLLinkElement, e: PointerEvent): void {
    e.preventDefault()
    const newState = link.getAttribute('data-state')

    this.switchView(newState)
  }

  protected switchView(state: string): void {
    const switcher = document.querySelectorAll('a.results__switcher__item')
    switcher.forEach(link => link.classList.remove('active'))
    document.querySelector('a.results__switcher__item[data-state="' + state + '"]').classList.add('active')

    document.querySelector('.list.users').classList.remove('list--list', 'list--cards')
    document.querySelector('.list.users').classList.add('list--' + state)

    document.querySelector('.frame-bwguild_userlist').classList.remove('view-list', 'view-cards')
    document.querySelector('.frame-bwguild_userlist').classList.add('view-' + state)

    localStorage.setItem('userResultList', state)
  }

  protected onLoadMoreClick(e: Event): void {
    e.preventDefault()
    const moreButton = e.currentTarget as HTMLLinkElement
    const nextButton = document.querySelector('.list.f3-widget-paginator li.next a')
    const url = nextButton.getAttribute('href')

    if (!moreButton) {
      return
    }

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
        nextButton?.setAttribute('href', newNextButton.getAttribute('href') ?? '')
      }

      // remove loading
      moreButton.classList.remove('loading')
    })
  }
}

const userSettingsObject = new Usersettings()

export default userSettingsObject
