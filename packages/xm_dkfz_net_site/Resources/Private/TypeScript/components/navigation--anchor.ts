class NavigationAnchor {
  public nav: HTMLElement
  public navItems: HTMLElement
  public navLinks: any[]
  public sections: NodeList
  public navButtons: HTMLElement[]

  constructor () {
    // only if element on the page
    if (document.querySelectorAll<HTMLElement>('.navigation--anchor').length !== 0) {
      // methods
      this.cacheDom()
      this.bindDomEvents()
      this.bindNavigationButtonEvents()
    }
  }

  public cacheDom (): void {
    this.nav = document.querySelector<HTMLElement>('.navigation--anchor')
    this.navItems = this.nav.querySelector<HTMLElement>('.navigation__items')
    this.navLinks = Array.from(this.navItems.querySelectorAll<HTMLElement>('.navigation__link'))
    this.navButtons = Array.from(this.nav.querySelectorAll<HTMLElement>('.navigation__button'))
    this.sections = document.querySelectorAll<HTMLElement>('.content-wrapper')

    this.navLinks.forEach(link => {
      link.addEventListener('click', (e: { preventDefault: () => void }) => {
        // Prevent the default link behavior
        e.preventDefault()

        // Get the href of the clicked link
        const href = link.getAttribute('href')

        // Get the element with the corresponding ID
        const element = document.querySelector(href)

        // Scroll to the element
        element.scrollIntoView({ behavior: 'smooth' })
      })
    })

    // Create an intersection observer for each section
    this.sections.forEach((section: HTMLElement) => {
      const observer = new IntersectionObserver((entries) => {
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

  protected bindDomEvents (): void {
    const currStickyPos = this.nav.getBoundingClientRect().top + window.scrollY
    document.addEventListener('scroll', () => {
      this.fixAnchorNavigationOnScroll(currStickyPos)
    })
  }

  protected bindNavigationButtonEvents (): void {
    const isOverflown = ({ clientWidth, clientHeight, scrollWidth, scrollHeight }:
    { clientWidth: number, clientHeight: number, scrollWidth: number, scrollHeight: number }): boolean => {
      return scrollHeight > clientHeight || scrollWidth > clientWidth
    }

    if (isOverflown(this.navItems)) {
      this.navButtons.forEach(button => {
        button.classList.add('active')

        if (button.classList.contains('left')) {
          button.classList.remove('active')
        }

        button.addEventListener('click', () => {
          this.sideScroll(this.navItems, button.dataset.direction, 25, 200, 150)
        })
      })

      this.navItems.addEventListener('scroll', () => {
        this.toggleNavigationScrollButtons(this.navItems)
      })
    }
  }

  protected sideScroll (element: HTMLElement, direction: string, speed: number, distance: number, step: number): void {
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

  toggleNavigationScrollButtons (element: HTMLElement): void {
    const leftButton: HTMLElement = this.nav.querySelector('button.left')
    const rightButton: HTMLElement = this.nav.querySelector('button.right')

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

  protected fixAnchorNavigationOnScroll (currStickyPos: number): void {
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

export default (new NavigationAnchor())
