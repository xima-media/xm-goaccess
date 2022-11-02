import './hero-form.scss'
import autocomplete from "autocompleter";

type AutocomleterItem = {
  label: string,
  value: string
}

class HeroForm {
  constructor() {
    this.initAutocompleter()
  }

  protected initAutocompleter() {
    const autocompleteInputs = document.querySelectorAll('.hero-form input.autocomplete') as HTMLInputElement[]

    if (autocompleteInputs) {
      autocompleteInputs.forEach(inputElement => this.initAutocompleterForInput(inputElement))
    }
  }

  protected initAutocompleterForInput(inputElement: HTMLInputElement) {
    
    let allItems = JSON.parse(inputElement.getAttribute('data-autocomplete'))

    allItems = allItems.map(item => {
      return {label: item, value: item}
    });

    autocomplete({
      input: inputElement,
      preventSubmit: true,
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
    })

    update(filteredFeatures)
  }

  protected onAutocompleteSelect(input: HTMLInputElement, item: AutocomleterItem) {
    input.value = item.value;
  }

}

export default (new HeroForm())
