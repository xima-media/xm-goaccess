class QuickSearch {
    quickSearchOpenState
    quickSearchEl
    quickSearchInputEl
    quickSearchButtonToggleEl

    constructor () {
        this.quickSearchOpenState = false
        this.quickSearchEl = document.querySelector<HTMLElement>('.quick-search')

        // main element existing?
        if (this.quickSearchEl) {
            this.quickSearchInputEl = this.quickSearchEl.querySelector('.field__input') as HTMLInputElement
            this.quickSearchButtonToggleEl = this.quickSearchEl.querySelector('.quick-search__button--toggle') as HTMLButtonElement

            // methods
            this.events()
        }
    }

    events () {
        const self = this

        // toggle: quick search
        self.quickSearchButtonToggleEl.addEventListener('click', () => self.toggleQuickSearch())

        // outside click
        document.addEventListener('mousedown', event => self.clickOutsideQuickSearch(event))
    }

    toggleQuickSearch () {
        const self = this

        // toggle 'open' class
        self.quickSearchEl.classList.toggle('fx--open')

        // toggle state
        self.quickSearchOpenState = !self.quickSearchOpenState

        // focus input element
        if (self.quickSearchOpenState) {
            self.quickSearchInputEl.focus()
        }

        // @todo aria-expanden togglen
    }

    clickOutsideQuickSearch (event: MouseEvent) {
        const self = this
        const targetEl = event.target as HTMLElement
        const targetParentEl = targetEl.closest('.quick-search') as HTMLElement

        if (targetParentEl === null && !targetEl.classList.contains('autocomplete-suggestion')) {
            if (self.quickSearchEl.classList.contains('fx--open')) {
                self.toggleQuickSearch()
            }
        }

        // @todo funktioniert nicht wenn auf carousel gedrückt wird
    }
}

export default (new QuickSearch())
