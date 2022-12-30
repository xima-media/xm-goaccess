class NavigationMain {
  buttonToggleMenuEl
  buttonToggleMenuItemsEl

  constructor() {
    this.buttonToggleMenuEl = document.querySelector<HTMLButtonElement>('.fx--toggle-main-menu')
    this.buttonToggleMenuItemsEl = document.querySelectorAll<HTMLButtonElement>('.navigation__button--toggle-items')

    if (this.buttonToggleMenuEl) {
      // methods
      this.events()
    }
  }

  events() {
    const self = this

    // toggle mobile menu
    self.buttonToggleMenuEl.addEventListener('click', () => self.toggleMobileMenu())

    // toggle mobile menu items
    self.buttonToggleMenuItemsEl.forEach(button => button.addEventListener('click', () => self.toggleMobileMenuItems(button)))
  }

  toggleMobileMenu() {
    // const self = this

    // @todo focus-trap hinzufügen
    // self.buttonToggleMenuEl @todo togle aria-attributwe

    document.documentElement.classList.toggle('fx--main-menu-open')
  }

  toggleMobileMenuItems(button: HTMLButtonElement) {
    // self.buttonToggleMenuEl @todo togle aria-attributwe

    // document.documentElement.classList.toggle('fx--main-menu-open')

    button.classList.toggle('fx--active')
  }
}

export default new NavigationMain()
