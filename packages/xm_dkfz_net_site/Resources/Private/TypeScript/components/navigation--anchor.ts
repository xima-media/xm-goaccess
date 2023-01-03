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
    const navButtons = document.querySelectorAll<HTMLElement>('.navigation--anchor .navigation__items .navigation__button')
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
    const currStickyPos = this.nav.getBoundingClientRect().top + window.scrollY
    document.addEventListener('scroll', () => {
      this.fixAnchorNavigationOnScroll(currStickyPos)
    })
  }

  protected bindNavigationLinksEvents(): void {
    this.navLinks.forEach(link => {
      link.addEventListener('click', (e: { preventDefault: () => void }) => {
        // Prevent the default link behavior
        e.preventDefault()

        // Get the href of the clicked link
        const sectionId = link.getAttribute('href')

        // Get the element with the corresponding ID
        if (sectionId) {
          const element = document.querySelector(sectionId)

          // Scroll to the element
          element?.scrollIntoView({ behavior: 'smooth' })
        }
      })
    })
  }

  protected registerSectionsIntersectionObserver(): void {
    // Create an intersection observer for each section
    this.sections.forEach((section: HTMLElement) => {
      const observer = new IntersectionObserver(entries => {
        // Check if the section is intersecting
        if (entries[0].isIntersecting) {
          // Get the ID of the section
          const id = section.getAttribute('id')

          // Find the corresponding navigation link
          const navLink = document.querySelector<HTMLLinkElement>(`.navigation--anchor a[href="#${id}"]`)

          if (navLink !== null) {
            // Add the "active" class to the link
            navLink.classList.add('active')
          }
        } else {
          // Find the corresponding navigation link
          const id = section.getAttribute('id')
          const navLink = document.querySelector(`.navigation--anchor a[href="#${id}"]`)

          if (navLink !== null) {
            // Remove the "active" class from the link
            navLink.classList.remove('active')
          }
        }
      })

      // Observe the section
      observer.observe(section)
    })
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
      this.navButtons.forEach(button => {
        button.classList.add('active')

        if (button.classList.contains('left')) {
          button.classList.remove('active')
        }

        button.addEventListener('click', () => {
          if (button?.dataset.direction) {
            this.sideScroll(this.navItems, button.dataset.direction, 25, 200, 150)
          }
        })
      })

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

  toggleNavigationScrollButtons(element: HTMLElement): void {
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

  protected fixAnchorNavigationOnScroll(currStickyPos: number): void {
    let thresholdNavigationBar = 124

    if (window.innerWidth <= 1800) {
      thresholdNavigationBar = 68
    }

    currStickyPos = currStickyPos - thresholdNavigationBar

    if (window.scrollY > currStickyPos) {
      this.nav.classList.add('navigation--anchor--fixed')
    } else {
      this.nav.classList.remove('navigation--anchor--fixed')
    }
  }
}

export default new NavigationAnchor()
