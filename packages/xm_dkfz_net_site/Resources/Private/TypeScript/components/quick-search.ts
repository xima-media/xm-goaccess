import autocomplete from 'autocompleter'

interface AutocomleterItem {
  label: string
  value: string
}

class QuickSearch {
  public quickSearchOpenState: boolean
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
    console.log('here')
    if (!this.quickSearchInputEl || !this.quickSearchEl) {
      return
    }

    this.quickSearchOpenState = false
    this.quickSearchInputEl.addEventListener('input', () => {
      if (this.quickSearchInputEl && this.quickSearchInputEl.value.length === 1) {
        const fillAutoCompleterList = async (): Promise<AutocomleterItem[] | any> => {
          let items: AutocomleterItem[]
          const url = this.quickSearchInputEl?.closest('form')?.dataset.url
          if (url) {
            items = await this.fetchAutocompleteItems(this.quickSearchInputEl, url)
          } else {
            return
          }

          if (!items) {
            return
          }

          autocomplete({
            input: this.quickSearchInputEl,
            minLength: 1,
            disableAutoSelect: true,
            fetch: this.onAutocompleteFetch.bind(this, items),
            onSelect: this.onAutocompleteSelect.bind(this, this.quickSearchInputEl)
          })
        }

        void fillAutoCompleterList()
      }
    })
  }

  protected async fetchAutocompleteItems(searchInput: HTMLInputElement, url: string): Promise<AutocomleterItem[] | any> {
    const autoCompleteListUrl = `${url}&wordStartsWith=${searchInput.value}`
    return await fetch(autoCompleteListUrl)
      .then(async response => await response.json())
      .then((autoCompleteList: any[]) => {
        if (autoCompleteList) {
          return autoCompleteList.map((item: any) => {
            return { label: item, value: item }
          }) as AutocomleterItem[]
        }
      })
      .catch(error => {
        console.error('Error when fetching results: ', error)
      })
  }

  protected onAutocompleteFetch(allItems: AutocomleterItem[], text: string, update: any): void {
    text = text.toLowerCase()

    const filteredFeatures = allItems
      .filter(item => {
        return item.value.toString().toLowerCase().includes(text)
      })
      .slice(0, 10)

    update(filteredFeatures)
  }

  protected onAutocompleteSelect(input: HTMLInputElement, item: AutocomleterItem): void {
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
