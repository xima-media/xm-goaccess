import autocomplete from 'autocompleter'

interface AutocomleterItem {
  label: string
  value: string
}

class QuickSearch {
  quickSearchOpenState: Boolean
  quickSearchEl: HTMLElement
  quickSearchInputEl: HTMLInputElement
  quickSearchButtonToggleEl: HTMLButtonElement

  constructor () {
    this.quickSearchOpenState = false
    this.quickSearchEl = document.querySelector('.quick-search')
    this.initAutoCompleter()

    // main element existing?
    if (this.quickSearchEl) {
      this.quickSearchInputEl = this.quickSearchEl.querySelector('.field__input')
      this.quickSearchButtonToggleEl = this.quickSearchEl.querySelector('.quick-search__button--toggle')

      // methods
      this.events()
    }
  }

  protected initAutoCompleter (): void {
    this.quickSearchInputEl.addEventListener('input', async () => {
      if (this.quickSearchInputEl.value.length === 1) {
        const allItems = await this.fetchAutocompleteItems(this.quickSearchInputEl)

        if (allItems != null) {
          autocomplete({
            input: this.quickSearchInputEl,
            minLength: 1,
            disableAutoSelect: true,
            fetch: await this.onAutocompleteFetch.bind(this, allItems),
            onSelect: this.onAutocompleteSelect.bind(this, this.quickSearchInputEl)
          })
        }
      }
    })
  }

  protected async fetchAutocompleteItems (searchInput: HTMLInputElement): Promise<void | AutocomleterItem[]> {
    const autoCompleteListUrl = `/index.php?eID=keSearchPremiumAutoComplete&wordStartsWith=${searchInput.value}&amount=10&pid=1`
    return await fetch(autoCompleteListUrl)
      .then(async (response) => await response.json())
      .then((autoCompleteList) => {
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

  protected onAutocompleteFetch (allItems: AutocomleterItem[], text: string, update: any) {
    text = text.toLowerCase()

    const filteredFeatures = allItems.filter((item) => {
      return item.value.toString().toLowerCase().includes(text)
    }).slice(0, 10)

    update(filteredFeatures)
  }

  protected onAutocompleteSelect (input: HTMLInputElement, item: AutocomleterItem) {
    input.value = item.value
    input.closest('form').submit()
  }

  events () {
    // toggle: quick search
    this.quickSearchButtonToggleEl.addEventListener('click', () => this.toggleQuickSearch())

    // outside click
    document.addEventListener('mousedown', event => this.clickOutsideQuickSearch(event))
  }

  toggleQuickSearch () {
    // toggle 'open' class
    this.quickSearchEl.classList.toggle('fx--open')

    // toggle state
    this.quickSearchOpenState = !this.quickSearchOpenState

    // focus input element
    if (this.quickSearchOpenState) {
      this.quickSearchInputEl.focus()
    }

    // @todo aria-expanden togglen
  }

  clickOutsideQuickSearch (event: MouseEvent) {
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
