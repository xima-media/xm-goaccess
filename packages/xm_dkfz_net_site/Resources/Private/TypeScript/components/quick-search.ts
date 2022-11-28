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
    const url = this.quickSearchInputEl.closest('form').dataset.url

    autocomplete({
      input: this.quickSearchInputEl,
      minLength: 1,
      disableAutoSelect: true,
      fetch: this.onAutocompleteFetch.bind(this, url),
      onSelect: this.onAutocompleteSelect.bind(this, this.quickSearchInputEl)
    })
  }

  protected async onAutocompleteFetch (url: string, text: string, update: any): Promise<void> {
    text = text.toLowerCase()

    const items = await this.fetchAutocompleteItems(`${url}&wordStartsWith=${text}`)

    if (items === null) {
      return
    }

    const filteredFeatures = items.filter((item: AutocomleterItem) => {
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

  protected async fetchAutocompleteItems (url: string): Promise<AutocomleterItem[] | any> {
    return await fetch(url)
      .then(async (response) => await response.json())
      .then((autoCompleteList: any[]) => {
        if (autoCompleteList) {
          return autoCompleteList.map((item: any) => {
            return { label: item, value: item }
          }) as AutocomleterItem[]
        } else {
          return null
        }
      })
      .catch(error => {
        console.error('Error while fetching results: ', error)
      })
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
