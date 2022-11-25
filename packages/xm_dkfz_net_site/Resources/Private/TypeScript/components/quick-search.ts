import autocomplete from 'autocompleter'

interface AutocomleterItem {
  label: string
  value: string
}

class QuickSearch {
  quickSearchOpenState
  quickSearchEl
  quickSearchInputEl
  quickSearchButtonToggleEl

  constructor () {
    this.quickSearchOpenState = false
    this.quickSearchEl = document.querySelector<HTMLElement>('.quick-search')
    this.initAutoCompleter()

    // main element existing?
    if (this.quickSearchEl) {
      this.quickSearchInputEl = this.quickSearchEl.querySelector('.field__input') as HTMLInputElement
      this.quickSearchButtonToggleEl = this.quickSearchEl.querySelector('.quick-search__button--toggle')

      // methods
      this.events()
    }
  }


  protected initAutoCompleter(): void {
    const searchInput = document.querySelector('#form_kesearch_pi3 .field__input[type="search"]') as HTMLInputElement

    searchInput.addEventListener('input', async () => {
      if(searchInput.value.length === 1) {
        const allItems = await this.fetchAutocompleteItems(searchInput)

        if(allItems) {
          autocomplete({
            input: searchInput,
            minLength: 1,
            disableAutoSelect: true,
            fetch: await this.onAutocompleteFetch.bind(this, allItems),
            onSelect: this.onAutocompleteSelect.bind(this, searchInput)
          })
        }
      }
    })
  }

  protected async fetchAutocompleteItems(searchInput: HTMLInputElement): Promise<void|AutocomleterItem[]> {
    const autoCompleteListUrl = `/index.php?eID=keSearchPremiumAutoComplete&wordStartsWith=${searchInput.value}&amount=10&pid=1`
    return await fetch(autoCompleteListUrl)
        .then((response) => response.json())
        .then((autoCompleteList) => {
          if(autoCompleteList) {
            return autoCompleteList.map((item: any) => {
              return {label: item, value: item}
            }) as AutocomleterItem[];
          }
        })
        .catch(error => {
          console.error('Error when fetching results: ', error);
        });
  }

  protected onAutocompleteFetch(allItems: AutocomleterItem[], text: string, update: any) {

    text = text.toLowerCase()

    const filteredFeatures = allItems.filter((item) => {
      return item.value.toString().toLowerCase().indexOf(text) >= 0
    }).slice(0, 10)

    update(filteredFeatures)
  }

  protected onAutocompleteSelect(input: HTMLInputElement, item: AutocomleterItem) {
    input.value = item.value;
    input.closest('form').submit();
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
    const targetParentEl = targetEl.closest('.quick-search')

    if (targetParentEl === null && !targetEl.classList.contains('autocomplete-suggestion')) {
      if (self.quickSearchEl.classList.contains('fx--open')) {
        self.toggleQuickSearch()
      }
    }

    // @todo funktioniert nicht wenn auf carousel gedrückt wird
  }
}

export default (new QuickSearch())
