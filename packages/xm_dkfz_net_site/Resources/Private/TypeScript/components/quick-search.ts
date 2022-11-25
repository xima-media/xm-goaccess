import autocomplete from 'autocompleter'

interface AutocomleterItem {
  label: string
  value: string
}

class QuickSearch {
  quickSearchOpenState: boolean
  quickSearchEl: HTMLElement
  quickSearchInputEl: HTMLInputElement
  quickSearchButtonToggleEl: HTMLButtonElement

  constructor () {
    this.quickSearchOpenState = false
    this.quickSearchEl = document.querySelector('.quick-search__wrapper')

    // main element existing?
    this.quickSearchInputEl = this.quickSearchEl.querySelector('.field__input')
    this.quickSearchButtonToggleEl = this.quickSearchEl.querySelector('.quick-search__button--toggle')

    this.initAutoCompleter()

    if (this.quickSearchButtonToggleEl != null) {
      this.bindQuickSearchButtonEvents()
    }
  }

  protected initAutoCompleter (): void {
    this.quickSearchInputEl.addEventListener('input', () => {
      if (this.quickSearchInputEl.value.length === 1) {
        const fillAutoCompleterList = async (): Promise<AutocomleterItem[] | any> => {
          const items = await this.fetchAutocompleteItems(this.quickSearchInputEl)

          if (items == null) {
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

  protected async fetchAutocompleteItems (searchInput: HTMLInputElement): Promise<AutocomleterItem[] | any> {
    const autoCompleteListUrl = `/index.php?eID=keSearchPremiumAutoComplete&wordStartsWith=${searchInput.value}&amount=10`
    return await fetch(autoCompleteListUrl)
      .then(async (response) => await response.json())
      .then((autoCompleteList) => {
        if (autoCompleteList != null) {
          return autoCompleteList.map((item: any) => {
            return { label: item, value: item }
          }) as AutocomleterItem[]
        }
      })
      .catch(error => {
        console.error('Error when fetching results: ', error)
      })
  }

  protected onAutocompleteFetch (allItems: AutocomleterItem[], text: string, update: any): void {
    text = text.toLowerCase()

    const filteredFeatures = allItems.filter((item) => {
      return item.value.toString().toLowerCase().includes(text)
    }).slice(0, 10)

    update(filteredFeatures)
  }

  protected onAutocompleteSelect (input: HTMLInputElement, item: AutocomleterItem): void {
    input.value = item.value
    const form = input.closest('form')

    if (form != null) {
      form.submit()
    }
  }

  bindQuickSearchButtonEvents (): void {
    // toggle: quick search
    this.quickSearchButtonToggleEl.addEventListener('click', () => this.toggleQuickSearch())

    // outside click
    document.addEventListener('mousedown', event => this.clickOutsideQuickSearch(event))
  }

  toggleQuickSearch (): void {
    // toggle 'open' class
    this.quickSearchEl.classList.toggle('fx--open')

    // toggle state
    // this.quickSearchOpenState = !this.quickSearchOpenState
    this.quickSearchOpenState = !this.quickSearchOpenState

    // focus input element
    if (this.quickSearchOpenState) {
      this.quickSearchInputEl.focus()
    }

    // @todo aria-expanden togglen
  }

  clickOutsideQuickSearch (event: MouseEvent): void {
    const targetEl = event.target as HTMLElement
    const targetParentEl = targetEl.closest('.quick-search')

    if (targetParentEl === null && !targetEl.classList.contains('autocomplete-suggestion')) {
      if (this.quickSearchEl.classList.contains('fx--open')) {
        this.toggleQuickSearch()
      }
    }

    // @todo funktioniert nicht wenn auf carousel gedrückt wird
  }
}

export default (new QuickSearch())
