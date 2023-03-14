import autocomplete from 'autocompleter'

interface AutocompleterItem {
  label: string
  value: string
}

class QuickSearch {
  public quickSearchEl: HTMLElement
  public quickSearchInputEl: HTMLInputElement
  public quickSearchButtonToggleEl: HTMLButtonElement | null
  public quickSearchFormEl: HTMLFormElement | null

  constructor() {
    if (!this.cacheDom()) {
      return
    }

    this.initAutoCompleter()
    this.bindQuickSearchButtonEvents()
  }

  protected cacheDom(): boolean {
    const quickSearchEl = document.querySelector<HTMLElement>('.quick-search')
    const quickSearchInputEl = document.querySelector<HTMLInputElement>('.quick-search__wrapper .field__input')
    const quickSearchButtonToggleEl = document.querySelector<HTMLButtonElement>('.quick-search__wrapper .quick-search__button--toggle')
    const quickSearchFormEl = document.querySelector<HTMLFormElement>('#form_kesearch_pi3')

    if (!quickSearchEl || !quickSearchInputEl) {
      return false
    }

    this.quickSearchEl = quickSearchEl
    this.quickSearchInputEl = quickSearchInputEl
    this.quickSearchButtonToggleEl = quickSearchButtonToggleEl
    this.quickSearchFormEl = quickSearchFormEl

    return true
  }

  protected initAutoCompleter(): void {
    if (!this.quickSearchInputEl || !this.quickSearchEl) {
      return
    }
    const url = this.quickSearchInputEl?.closest('form')?.dataset.url

    autocomplete({
      input: this.quickSearchInputEl,
      minLength: 1,
      disableAutoSelect: true,
      fetch: this.onAutocompleteFetch.bind(this, url),
      onSelect: this.onAutocompleteSelect.bind(this, this.quickSearchInputEl)
    })
  }

  protected async onAutocompleteFetch(url: string, text: string, update: any): Promise<void> {
    text = text.toLowerCase()
    const autoCompleteListUrl = `${url}&wordStartsWith=${text}`
    const items = await this.fetchAutocompleteItems(autoCompleteListUrl)

    if (!items) {
      return
    }

    const filteredFeatures = items
      .filter((item: { value: { toString: () => string } }) => {
        return item.value.toString().toLowerCase().includes(text)
      })
      .slice(0, 10)

    update(filteredFeatures)
  }

  protected async fetchAutocompleteItems(url: string): Promise<AutocompleterItem[] | any> {
    return await fetch(url)
      .then(async response => await response.json())
      .then((autoCompleteList: any[]) => {
        if (autoCompleteList) {
          return autoCompleteList.map((item: any) => {
            return { label: item, value: item }
          }) as AutocompleterItem[]
        }
      })
      .catch(error => {
        console.error('Error when fetching results: ', error)
      })
  }

  protected onAutocompleteSelect(input: HTMLInputElement, item: AutocompleterItem): void {
    input.value = item.value
    const form: HTMLFormElement | null = input.closest('form')

    form?.submit()
  }

  protected bindQuickSearchButtonEvents(): void {
    if (!this.quickSearchButtonToggleEl) {
      return
    }

    this.quickSearchButtonToggleEl.addEventListener('click', () => {
      this.handleQuickSearchButtonClick()
    })

    this.quickSearchInputEl.addEventListener('input', () => {
      this.handleQuickSearchInput()
    })

    document.addEventListener('mousedown', event => {
      this.clickOutsideQuickSearch(event)
    })
  }

  protected handleQuickSearchButtonClick(): void {
    const searchBox = this.quickSearchButtonToggleEl?.closest('.search-box')
    const navigationItems = this.quickSearchButtonToggleEl?.closest('.navigation__items')

    if (searchBox) {
      searchBox.classList.add('search-box--active')
      navigationItems?.classList.add('search-box--active')
    }
    this.quickSearchInputEl.focus()
    if (this.quickSearchInputEl.value.length !== 0) {
      setTimeout(() => {
        this.quickSearchButtonToggleEl?.setAttribute('type', 'submit')
      }, 500)
    }
  }

  protected handleQuickSearchInput(): void {
    if (this.quickSearchInputEl.value.length !== 0) {
      this.quickSearchButtonToggleEl?.setAttribute('type', 'submit')
    }
  }

  protected clickOutsideQuickSearch(event: MouseEvent): void {
    if (!this.quickSearchButtonToggleEl) return

    const targetEl = event.target as HTMLElement
    const targetParentEl = targetEl.closest('.quick-search')

    if (targetParentEl === null && !targetEl.classList.contains('autocomplete-suggestion')) {
      this.quickSearchButtonToggleEl.setAttribute('type', 'button')
      const searchBox = this.quickSearchButtonToggleEl.closest('.search-box')
      if (searchBox?.classList.contains('search-box--active')) {
        searchBox?.classList.remove('search-box--active')
        this.quickSearchButtonToggleEl.closest('.navigation__items')?.classList.remove('search-box--active')
      }
    }
  }
}

export default new QuickSearch()
