class NavigationMain {
  buttonToggleMenuEl: HTMLButtonElement
  buttonToggleMenuItemsEl: NodeListOf<HTMLButtonElement>

  constructor() {
    if (!this.cacheDom()) {
      return
    }

    this.events()
  }

  cacheDom(): boolean {
    const buttonToggleMenuEl = document.querySelector<HTMLButtonElement>('.fx--toggle-main-menu')
    const buttonToggleMenuItemsEl = document.querySelectorAll<HTMLButtonElement>('.navigation__button--toggle-items')

    if (!buttonToggleMenuEl || !buttonToggleMenuItemsEl) {
      return false
    }

    this.buttonToggleMenuEl = buttonToggleMenuEl
    this.buttonToggleMenuItemsEl = buttonToggleMenuItemsEl
    return true
  }

  events(): void {
    // toggle mobile menu
    this.buttonToggleMenuEl.addEventListener('click', () => this.toggleMobileMenu())

    // toggle mobile menu items
    this.buttonToggleMenuItemsEl.forEach(button => button.addEventListener('click', () => this.toggleMobileMenuItems(button)))
  }

  toggleMobileMenu(): void {
    // const self = this

    // @todo focus-trap hinzufügen
    // self.buttonToggleMenuEl @todo togle aria-attributwe

    document.documentElement.classList.toggle('fx--main-menu-open')
  }

  toggleMobileMenuItems(button: HTMLButtonElement): void {
    // self.buttonToggleMenuEl @todo togle aria-attributwe

    // document.documentElement.classList.toggle('fx--main-menu-open')

    button.classList.toggle('fx--active')
  }
}

export default new NavigationMain()
