import app from './basic'

import autocomplete, {AutocompleteItem} from 'autocompleter'
import {AutocomleterItem} from "./hero-form";

interface FeatureItem extends AutocompleteItem {
  label: string
  value: string
}

class Userprofile {
  constructor() {
    this.bindEvents()
  }

  protected bindEvents() {
    this.bindProfileEditLink()
  }

  protected bindProfileEditLink() {
    document.querySelectorAll('a[data-user-edit-link]').forEach((link) => {
      link.addEventListener('click', this.onUserProfileEditLinkClick.bind(this))
    })
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
    return await app.apiRequest(url).then(data => {
      return data.html
    })
  }

  protected bindUserEditFormEvents() {
    const form = app.lightbox.content.querySelector('form')
    this.initUserRepresentativeAutocomplete()
    this.initUserCommitteeRepresentativeAutocomplete()
    this.initUserFeatureInputs()
    form.addEventListener('submit', this.onUserEditFormSubmit.bind(this))
    form.querySelector('button[data-abort]').addEventListener('click', this.onAbortButtonClick.bind(this))
  }

  protected initUserRepresentativeAutocomplete() {
    const inputElement = app.lightbox.content.querySelector('#representative') as HTMLInputElement
    const hiddenElement = app.lightbox.content.querySelector('#representativeHiddenInput') as HTMLInputElement

    if (!inputElement || !hiddenElement) {
      return
    }

    const allItems = JSON.parse(inputElement.getAttribute('data-autocomplete')) as AutocompleteItem[]

    autocomplete({
      input: inputElement,
      minLength: 2,
      disableAutoSelect: true,
      preventSubmit: true,
      fetch: this.onUserRepresentativeAutocompleteFetch.bind(this, allItems),
      onSelect: this.onUserRepresentativeAutocompleteSelect.bind(this, inputElement, hiddenElement)
    })
  }

  protected initUserCommitteeRepresentativeAutocomplete() {
    const inputElement = app.lightbox.content.querySelector('#user-committee-representative') as HTMLInputElement
    const hiddenElement = app.lightbox.content.querySelector('#committeeRepresentativeHiddenInput') as HTMLInputElement

    if (!inputElement || !hiddenElement) {
      return
    }

    const allItems = JSON.parse(inputElement.getAttribute('data-autocomplete')) as AutocompleteItem[]

    autocomplete({
      input: inputElement,
      minLength: 2,
      disableAutoSelect: true,
      preventSubmit: true,
      fetch: this.onUserRepresentativeAutocompleteFetch.bind(this, allItems),
      onSelect: this.onUserRepresentativeAutocompleteSelect.bind(this, inputElement, hiddenElement)
    })
  }

  protected onUserRepresentativeAutocompleteFetch(allItems: AutocomleterItem[], text: string, update: any) {
    text = text.toLowerCase()

    const filteredUsers = allItems.filter((item) => {
      return item.label.toString().toLowerCase().includes(text)
    }).slice(0, 10)

    update(filteredUsers)
  }

  protected onUserRepresentativeAutocompleteSelect(input: HTMLInputElement, hiddenInput: HTMLInputElement, item: AutocomleterItem) {
    input.value = item.label
    hiddenInput.value = item.value
  }

  protected initUserFeatureInputs() {
    const featureBubbleTemplate = document.querySelector('a[data-feature="###JS_TEMPLATE###"]')
    const selectElement = document.querySelector('select[name="tx_bwguild_api[user][features][]"]')

    app.lightbox.content.querySelectorAll('div[data-all-features]').forEach((container) => {
      const inputElement = container.querySelector('input')
      const bubbleDropZoneElement = container.querySelector('ul.list')
      const recordType = container.getAttribute('data-record-type')
      const allFeaturesJson = container.getAttribute('data-all-features')
      const allFeatures = allFeaturesJson ? JSON.parse(allFeaturesJson) as FeatureItem[] : []
      const selectedFeaturesJson = container.getAttribute('data-selected-features')
      let selectedFeatures = selectedFeaturesJson ? JSON.parse(selectedFeaturesJson) as FeatureItem[] : []

      function hashCode(str: string) {
        let hash = 0
        for (let i = 0, len = str.length; i < len; i++) {
          const chr = str.charCodeAt(i)
          hash = (hash << 5) - hash + chr
          hash |= 0 // Convert to 32bit integer
        }
        return hash
      }

      function onBubbleClick(e: Event) {
        e.preventDefault()
        const featureElement = e.currentTarget as HTMLLinkElement
        const featureId = featureElement.getAttribute('data-feature')
        // remove from selectable items
        selectedFeatures = selectedFeatures.filter(feature => feature.value !== featureId)

        // remove from form selection OR remove created newly created hidden element
        const isPersisted = parseInt(featureId) > 0
        if (isPersisted) {
          const optionElement = selectElement.querySelector('option[value="' + featureId + '"]')
          optionElement.removeAttribute('selected')
        } else {
          container.querySelectorAll('input[name^="tx_bwguild_api[user][features][' + featureId + ']"]').forEach(el => el.remove())
        }
        // delete element
        featureElement.remove()
      }

      function addBubbleClickEvent(bubbleElement: Element) {
        bubbleElement.addEventListener('click', onBubbleClick)
      }

      function addNewBubbleForFeature(feature: FeatureItem) {
        const newFeatureBubble = featureBubbleTemplate.cloneNode(true) as HTMLLinkElement
        newFeatureBubble.setAttribute('data-feature', feature.value)
        newFeatureBubble.querySelector('span').innerHTML = feature.label
        newFeatureBubble.classList.remove('d-none')
        addBubbleClickEvent(newFeatureBubble)
        bubbleDropZoneElement.appendChild(newFeatureBubble)
      }

      function onAutocompleteSelect(item: FeatureItem) {
        // create new bubble
        addNewBubbleForFeature(item)
        // add as selected in form
        const optionElement = selectElement.querySelector('option[value="' + item.value + '"]')
        optionElement.setAttribute('selected', 'selected')
        // add to selected list
        selectedFeatures.push(item)
        // clear input
        inputElement.value = ''
      }

      function onAutocompleteFetch(text: string, update: any) {
        text = text.toLowerCase()
        const filteredFeatures = allFeatures.filter((feature) => {
          const isMatchedByTextSearch = feature.label.toLowerCase().includes(text)
          const isNotAlreadySelected = selectedFeatures.find(f => f.value === feature.value) === undefined
          return isMatchedByTextSearch && isNotAlreadySelected
        })
        update(filteredFeatures)
      }

      function onNewFeatureEntered() {
        const trimmedInputString = inputElement.value.trim()
        const newFeatureId = 'NEW' + hashCode(trimmedInputString)
        const isAlreadySelected = selectedFeatures.find(f => f.label === trimmedInputString) !== undefined

        if (isAlreadySelected) {
          return
        }

        // create new feature
        const newFeatureItem: FeatureItem = {label: trimmedInputString, value: newFeatureId}
        addNewBubbleForFeature(newFeatureItem)

        // add to select list
        selectedFeatures.push(newFeatureItem)

        // create hidden form elements (name & record_type)
        const hiddenElementRt = document.createElement('input')
        hiddenElementRt.value = recordType
        hiddenElementRt.setAttribute('type', 'hidden')
        hiddenElementRt.setAttribute('name', 'tx_bwguild_api[user][features][' + newFeatureId + '][recordType]')
        container.append(hiddenElementRt)
        const hiddenElementNa = document.createElement('input')
        hiddenElementNa.value = newFeatureItem.label
        hiddenElementNa.setAttribute('type', 'hidden')
        hiddenElementNa.setAttribute('name', 'tx_bwguild_api[user][features][' + newFeatureId + '][name]')
        container.append(hiddenElementNa)

        // clear input
        inputElement.value = ''
      }

      bubbleDropZoneElement.querySelectorAll('a[data-feature]').forEach(bubbleElement => addBubbleClickEvent(bubbleElement))

      inputElement.addEventListener('keydown', (e) => {
        const isEnterKey = e.key === 'Enter'
        const isAutocompleteOptionSelected = document.querySelector('.autocomplete .selected')
        const hasMinLength = inputElement.value.length > 2

        if (isEnterKey && !isAutocompleteOptionSelected && hasMinLength) {
          onNewFeatureEntered()
        }
      })

      autocomplete({
        input: inputElement,
        preventSubmit: true,
        minLength: 1,
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

  protected onAbortButtonClick(e: Event) {
    e.preventDefault()
    app.lightbox.close()
  }
}

export default (new Userprofile())
