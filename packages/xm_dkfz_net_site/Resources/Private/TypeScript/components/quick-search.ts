import autocomplete, { AutocompleteResult, AutocompleteSettings } from 'autocompleter'

interface AutocomleterItem {
  label: string
  value: string
}

class QuickSearch {
  quickSearchOpenState: boolean
  quickSearchEl: HTMLElement
  quickSearchInputEl: HTMLInputElement
  quickSearchButtonToggleEl: HTMLButtonElement
  quickSearchFormEl: HTMLFormElement

  constructor () {
    this.cacheDom()

    if (this.quickSearchEl) {
      this.initAutoCompleter()
    }

    if (this.quickSearchButtonToggleEl) {
      this.bindQuickSearchButtonEvents()
    }
  }

  protected cacheDom (): void {
    this.quickSearchEl = document.querySelector('.quick-search')
    this.quickSearchInputEl = document.querySelector('.quick-search__wrapper .field__input')
    this.quickSearchButtonToggleEl = document.querySelector('.quick-search__wrapper .quick-search__button--toggle')
    this.quickSearchFormEl = document.querySelector<HTMLFormElement>('#form_kesearch_pi3')
  }

  protected initAutoCompleter (): void {
    this.quickSearchOpenState = false
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
    const autoCompleteListUrl = `${searchInput.closest('form').dataset.url}&wordStartsWith=${searchInput.value}`
    return await fetch(autoCompleteListUrl)
      .then(async (response) => await response.json())
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
    this.quickSearchButtonToggleEl.addEventListener('click', e => {
      e.preventDefault()
      this.quickSearchButtonToggleEl.closest('.search-box').classList.add('search-box--active')
      this.quickSearchInputEl.focus()
    })

    this.quickSearchInputEl.addEventListener('input', () => {
      if (this.quickSearchInputEl.value.length !== 0) {
        this.quickSearchButtonToggleEl.addEventListener('click', e => {
          this.quickSearchFormEl.submit()
        })
      }
    })

    document.addEventListener('mousedown', event => this.clickOutsideQuickSearch(event))
  }

  clickOutsideQuickSearch (event: MouseEvent): void {
    const targetEl = event.target as HTMLElement
    const targetParentEl = targetEl.closest('.quick-search')

    if (targetParentEl === null && !targetEl.classList.contains('autocomplete-suggestion')) {
      if (this.quickSearchButtonToggleEl.closest('.search-box').classList.contains('search-box--active')) {
        this.quickSearchButtonToggleEl.closest('.search-box').classList.remove('search-box--active')
      }
    }
  }
}

export default (new QuickSearch())
