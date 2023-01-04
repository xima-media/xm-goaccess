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

    // toggle: quick search
    this.quickSearchButtonToggleEl.addEventListener('click', () => {
      this.quickSearchButtonToggleEl?.closest('.search-box')?.classList.add('search-box--active')
      this.quickSearchButtonToggleEl?.closest('.navigation__items')?.classList.add('search-box--active')
      this.quickSearchInputEl.focus()

      if (
        this.quickSearchInputEl.value.length !== 0 &&
        this.quickSearchButtonToggleEl &&
        this.quickSearchButtonToggleEl.closest('.search-box')?.classList.contains('search-box--active') &&
        this.quickSearchButtonToggleEl.closest('.navigation__items')?.classList.contains('search-box--active')
      ) {
        setTimeout(() => {
          this.quickSearchButtonToggleEl?.setAttribute('type', 'submit')
        }, 500)
      }
    })

    this.quickSearchInputEl.addEventListener('input', () => {
      if (
        this.quickSearchInputEl.value.length !== 0 &&
        this.quickSearchButtonToggleEl &&
        this.quickSearchButtonToggleEl.closest('.search-box')?.classList.contains('search-box--active') &&
        this.quickSearchButtonToggleEl.closest('.navigation__items')?.classList.contains('search-box--active')
      ) {
        this.quickSearchButtonToggleEl.setAttribute('type', 'submit')
      }
    })

    document.addEventListener('mousedown', event => {
      this.clickOutsideQuickSearch(event)
    })
  }

  protected clickOutsideQuickSearch(event: MouseEvent): void {
    const targetEl = event.target as HTMLElement
    const targetParentEl = targetEl.closest('.quick-search')

    if (!this.quickSearchButtonToggleEl) {
      return
    }

    if (targetParentEl === null && !targetEl.classList.contains('autocomplete-suggestion')) {
      this.quickSearchButtonToggleEl.setAttribute('type', 'button')
      if (this.quickSearchButtonToggleEl.closest('.search-box')?.classList.contains('search-box--active')) {
        this.quickSearchButtonToggleEl.closest('.search-box')?.classList.remove('search-box--active')
        this.quickSearchButtonToggleEl.closest('.navigation__items')?.classList.remove('search-box--active')
      }
    }
  }
}

export default new QuickSearch()
