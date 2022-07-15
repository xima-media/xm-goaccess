import app from '../basic/basic'
import './userprofile.scss'
import autocomplete, {AutocompleteItem} from "autocompleter";
import dropdown from "../dropdown/dropdown";

interface FeatureItem extends AutocompleteItem {
  label: string,
  value: number
}

class Userprofile {

  constructor() {
    app.log('component "userinfo" loaded')

    this.bindEvents()
  }

  protected bindEvents() {
    this.bindProfileEditLink()
  }

  protected bindProfileEditLink() {
    document.querySelectorAll('a[data-user-edit-link]').forEach((link) => {
      link.addEventListener('click', this.onUserProfileEditLinkClick.bind(this))
    });
  }

  protected onUserProfileEditLinkClick(e: Event) {
    e.preventDefault()
    const link = e.currentTarget as Element
    const url = link.getAttribute('data-user-edit-link')

    app.lightbox.startLoading()
    app.lightbox.open()

    this.loadUserEditForm(url).then((formHtml) => {
      app.lightbox.displayContent(formHtml)
      this.bindUserEditFormEvents()
      app.lightbox.stopLoading()
    })
  }

  protected async loadUserEditForm(url: string) {
    return app.apiRequest(url).then(data => {
      return data.html
    })
  }

  protected bindUserEditFormEvents() {
    const form = app.lightbox.content.querySelector('form')
    this.initUserFeatureInputs()
    form.addEventListener('submit', this.onUserEditFormSubmit.bind(this))
  }

  protected initUserFeatureInputs() {

    const featureBubbleTemplate = document.querySelector('a[data-feature="###JS_TEMPLATE###"]') as HTMLLinkElement

    app.lightbox.content.querySelectorAll('div[data-all-features]').forEach((container) => {

      const inputElement = container.querySelector('input') as HTMLInputElement
      const allFeatures = JSON.parse(container.getAttribute('data-all-features')) as FeatureItem[]
      let selectedFeatures = JSON.parse(container.getAttribute('data-selected-features')) as FeatureItem[]
      const bubbleDropZone = container.querySelector('ul.list') as HTMLDivElement

      function onBubbleClick(e: Event) {
        e.preventDefault()
        const featureElement = e.currentTarget as HTMLLinkElement
        const featureId = parseInt(featureElement.getAttribute('data-feature'))
        // remove from selected items
        selectedFeatures = selectedFeatures.filter(feature => feature.value !== featureId)
        featureElement.remove()
      }

      function addBubbleClickEvent(bubbleElement: Element) {
        bubbleElement.addEventListener('click', onBubbleClick)
      }

      function onAutocompleteSelect(item: FeatureItem) {
        let newFeatureBubble = featureBubbleTemplate.cloneNode(true) as HTMLLinkElement;
        newFeatureBubble.setAttribute('data-feature', item.value.toString())
        newFeatureBubble.querySelector('span').innerHTML = item.label
        newFeatureBubble.classList.remove('d-none')
        addBubbleClickEvent(newFeatureBubble)
        bubbleDropZone.appendChild(newFeatureBubble)
        selectedFeatures.push(item)
      }

      function onAutocompleteFetch(text: string, update: any) {
        text = text.toLowerCase()
        const filteredFeatures = allFeatures.filter((feature) => {
          const isMatchedByTextSearch = feature.label.toLowerCase().indexOf(text) >= 0
          const isNotAlreadySelected = selectedFeatures.find(f => f.value === feature.value) === undefined
          return isMatchedByTextSearch && isNotAlreadySelected;
        })
        update(filteredFeatures)
      }

      bubbleDropZone.querySelectorAll('a[data-feature]').forEach(bubbleElement => addBubbleClickEvent(bubbleElement))

      autocomplete({
        input: inputElement,
        preventSubmit: true,
        minLength: 1,
        showOnFocus: true,
        disableAutoSelect: true,
        fetch: onAutocompleteFetch,
        onSelect: onAutocompleteSelect
      })
    })
  }

  protected onUserEditFormSubmit(e: Event) {
    e.preventDefault()

    const form = e.currentTarget as HTMLFormElement
    const url = form.getAttribute('action')

    app.lightbox.startLoading()
    app.apiRequest(url, 'POST', form)
      .then(data => {
        app.lightbox.displayContent(data.html)
        this.bindUserEditFormEvents()
        app.lightbox.stopLoading()
      })
      .catch(e => app.handleRequestError.bind(this))
  }


}

export default (new Userprofile())
