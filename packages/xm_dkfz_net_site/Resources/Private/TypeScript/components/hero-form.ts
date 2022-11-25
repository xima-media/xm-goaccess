import autocomplete from "autocompleter";

type AutocomleterItem = {
  label: string,
  value: string
}

class HeroForm {
  constructor() {
    this.initAutocompleter()
    this.bindSearchFormEvents()
  }

  protected initAutocompleter() {
    const autocompleteInputs = document.querySelectorAll('.hero-form input.autocomplete') as NodeListOf<HTMLInputElement>

    if (autocompleteInputs) {
      autocompleteInputs.forEach(inputElement => this.initAutocompleterForInput(inputElement))
    }
  }

  protected initAutocompleterForInput(inputElement: HTMLInputElement) {

    const autocompleterData = JSON.parse(inputElement.getAttribute('data-autocomplete')) as String[]

    const allItems = autocompleterData.map(item => {
      return {label: item, value: item}
    }) as AutocomleterItem[];

    autocomplete({
      input: inputElement,
      minLength: 1,
      disableAutoSelect: true,
      fetch: this.onAutocompleteFetch.bind(this, allItems),
      onSelect: this.onAutocompleteSelect.bind(this, inputElement)
    })
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
  }

  protected bindSearchFormEvents() {
    this.bindResultListCheckbox()
    this.bindResultListResetButton()
    this.bindResultListSortingSelect()
    this.bindFilterExpandButton()
  }

  protected bindFilterExpandButton() {
    const filterExpandButton = document.querySelector('#form_kesearch_pi1 + .hero-form__filter-button')
    const filterContainer = document.querySelector('.frame-ke_search_pi2 .filter.filter--results')

    if(filterExpandButton && filterContainer) {
      filterExpandButton.addEventListener('click', () => {
        ['show-for-md'].map(classes=> filterContainer.classList.toggle(classes) )
        filterExpandButton.querySelector('svg').classList.toggle('icon--rotated')
      })
    }
  }

  protected bindResultListCheckbox() {
    document.querySelectorAll('.filter--results input[type="checkbox"]').forEach((checkbox) => {
      checkbox.addEventListener('change', this.onResultListCheckboxChange.bind(this))
    });
  }

  protected onResultListCheckboxChange(e: Event) {
    const resultListCheckbox = e.currentTarget as HTMLInputElement
    const form = document.getElementById('form_kesearch_pi1') as HTMLFormElement
    const searchFormCheckbox = form.querySelector(`input[name="${resultListCheckbox.name}"]`) as HTMLInputElement

    searchFormCheckbox.checked = resultListCheckbox.checked

    form.submit()
  }

  protected bindResultListResetButton() {
    document.querySelectorAll('.filter--remove').forEach((resetButton) => {
      resetButton.addEventListener('click', this.onResultListResetButtonClick.bind(this))
    });
  }

  protected onResultListResetButtonClick(e: Event) {
    const resetButton = e.currentTarget as HTMLButtonElement
    const filterList = document.getElementById(resetButton.dataset.filterlistId) as HTMLUListElement
    const form = document.getElementById('form_kesearch_pi1') as HTMLFormElement

    filterList.querySelectorAll('input[type="checkbox"]').forEach((resultListCheckbox: HTMLInputElement) => {
      let searchFormCheckbox = form.querySelector(`input[name="${resultListCheckbox.name}"]`) as HTMLInputElement
      resultListCheckbox.checked = searchFormCheckbox.checked = false;
    });

    form.submit()
  }

  protected bindResultListSortingSelect() {
    document.querySelectorAll('select[name="field__input--name-sorting"]').forEach((sortingSelect: HTMLSelectElement) => {
      sortingSelect.addEventListener('change', this.onResultListSortingSelectChange.bind(this))
    });
  }

  protected onResultListSortingSelectChange(e: Event) {
    const selectField = e.currentTarget as HTMLSelectElement
    const selectedOption = selectField.selectedOptions[0] as HTMLOptionElement

    console.log(selectedOption)

    if (selectedOption) {
      location.href = selectedOption.value
    }
  }

}

export default (new HeroForm())
