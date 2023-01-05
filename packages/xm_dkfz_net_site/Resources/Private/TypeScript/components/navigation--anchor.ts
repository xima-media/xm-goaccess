class NavigationAnchor {
  public nav: HTMLElement
  public navItems: HTMLElement
  public navLinks: NodeListOf<HTMLElement>
  public sections: NodeListOf<HTMLElement>
  public navButtons: NodeListOf<HTMLElement>

  constructor() {
    if (!this.cacheDom()) {
      return
    }

    this.events()
    this.bindNavigationButtonEvents()
    this.bindNavigationLinksEvents()
    this.registerSectionsIntersectionObserver()
  }

  public cacheDom(): boolean {
    const nav = document.querySelector<HTMLElement>('.navigation--anchor')
    const navItems = document.querySelector<HTMLElement>('.navigation--anchor .navigation__items')
    const navLinks = document.querySelectorAll<HTMLElement>('.navigation--anchor .navigation__items .navigation__link')
    const navButtons = document.querySelectorAll<HTMLElement>('.navigation--anchor .navigation__button')
    const sections = document.querySelectorAll<HTMLElement>('.content-wrapper')

    if (!nav || !navItems || !navLinks || !navButtons || !sections) {
      return false
    }

    this.nav = nav
    this.navItems = navItems
    this.navLinks = navLinks
    this.navButtons = navButtons
    this.sections = sections

    return true
  }

  protected events(): void {
    const currentStickyPos = this.nav.getBoundingClientRect().top + window.scrollY
    document.addEventListener('scroll', () => {
      this.fixAnchorNavigationOnScroll(currentStickyPos)
    })
  }

  protected bindNavigationLinksEvents(): void {
    for (const link of this.navLinks) {
      link.addEventListener('click', (event: { preventDefault: () => void }) => {
        // Prevent the default link behavior
        event.preventDefault()

        // Get the href of the clicked link
        const sectionId = link.getAttribute('href')

        // Get the element with the corresponding ID
        if (sectionId) {
          const element = document.querySelector(sectionId)

          // Scroll to the element
          element?.scrollIntoView({ behavior: 'smooth' })
        }
      })
    }
  }

  protected registerSectionsIntersectionObserver(): void {
    // Create an intersection observer for each section
    for (const section of this.sections) {
      const observer = new IntersectionObserver(entries => {
        const id = section.getAttribute('id') as string
        const navLink = document.querySelector<HTMLLinkElement>(`.navigation--anchor a[href="#${id}"]`)
        // Check if the section is intersecting
        if (entries[0].isIntersecting) {
          if (navLink) {
            navLink.parentElement?.classList.add('active')
            navLink.classList.add('active')
          }
        } else {
          if (navLink) {
            navLink.parentElement?.classList.remove('active')
            navLink.classList.remove('active')
          }
        }
      })

      // Observe the section
      observer.observe(section)
    }
  }

  protected bindNavigationButtonEvents(): void {
    const isOverflown = ({
      clientWidth,
      clientHeight,
      scrollWidth,
      scrollHeight
    }: {
      clientWidth: number
      clientHeight: number
      scrollWidth: number
      scrollHeight: number
    }): boolean => {
      return scrollHeight > clientHeight || scrollWidth > clientWidth
    }

    if (isOverflown(this.navItems)) {
      for (const button of this.navButtons) {
        button.classList.add('active')

        if (button.classList.contains('left')) {
          button.classList.remove('active')
        }

        button.addEventListener('click', () => {
          if (button?.dataset.direction) {
            this.sideScroll(this.navItems, button.dataset.direction, 25, 200, 150)
          }
        })
      }

      this.navItems.addEventListener('scroll', () => {
        this.toggleNavigationScrollButtons(this.navItems)
      })
    }
  }

  protected sideScroll(element: HTMLElement, direction: string, speed: number, distance: number, step: number): void {
    let scrollAmount = 0

    const slideTimer = setInterval(function () {
      if (direction === 'left') {
        element.scrollLeft -= step
      } else {
        element.scrollLeft += step
      }
      scrollAmount += step
      if (scrollAmount >= distance) {
        window.clearInterval(slideTimer)
      }
    }, speed)
  }

  protected toggleNavigationScrollButtons(element: HTMLElement): void {
    const leftButton = this.nav.querySelector<HTMLButtonElement>('button.left')
    const rightButton = this.nav.querySelector<HTMLButtonElement>('button.right')

    if (!leftButton || !rightButton) {
      return
    }

    setTimeout(() => {
      if (element.scrollLeft > 5) {
        leftButton.classList.add('active')
      } else {
        leftButton.classList.remove('active')
      }

      if (element.scrollWidth - element.offsetWidth === element.scrollLeft) {
        rightButton.classList.remove('active')
      } else {
        rightButton.classList.add('active')
      }
    }, 300)
  }

  protected fixAnchorNavigationOnScroll(currentStickyPos: number): void {
    let thresholdNavigationBar = 124

    if (window.innerWidth <= 1800) {
      thresholdNavigationBar = 68
    }

    currentStickyPos = currentStickyPos - thresholdNavigationBar

    if (window.scrollY > currentStickyPos) {
      this.nav.classList.add('navigation--anchor--fixed')
    } else {
      this.nav.classList.remove('navigation--anchor--fixed')
    }
  }
}

export default new NavigationAnchor()
