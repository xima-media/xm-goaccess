import app from './basic'

import autocomplete, { AutocompleteItem } from 'autocompleter'
import { AutocomleterItem } from './hero-form'
import { NoticeStyle } from './notice'

interface FeatureItem extends AutocompleteItem {
  label: string
  value: string
}

class Userprofile {
  constructor() {
    this.bindEvents()
  }

  protected bindEvents(): void {
    this.bindProfileEditLink()
  }

  protected bindProfileEditLink(): void {
    document.querySelectorAll('[data-user-edit-link]').forEach(link => {
      link.addEventListener('click', this.onUserProfileEditLinkClick.bind(this))
    })
  }

  protected onUserProfileEditLinkClick(e: Event): void {
    e.preventDefault()
    const link = e.currentTarget as Element
    const url = link.getAttribute('data-user-edit-link') ?? ''

    app.lightbox.startLoading()
    app.lightbox.open()
    this.loadUserEditForm(url)
      .then(formHtml => {
        app.lightbox.displayContent(formHtml)
        this.bindUserEditFormEvents()
        app.lightbox.stopLoading()
      })
      .catch(() => {
        // app.notice.open(NoticeStyle.error, 'lorem')
      })
  }

  protected async loadUserEditForm(url: string): Promise<any> {
    return await app.apiRequest(url).then(data => {
      return data.html
    })
  }

  protected bindUserEditFormEvents(): void {
    const form = app.lightbox.content.querySelector('form')
    this.initUserImageDeleteClick()
    this.initUserRepresentativeSelect()
    this.initUserRepresentativeAutocomplete()
    this.initUserCommitteeRepresentativeAutocomplete()
    this.initUserFeatureInputs()
    this.initClearLinks()
    form?.addEventListener('submit', this.onUserEditFormSubmit.bind(this))
    form?.querySelector('button[data-abort]')?.addEventListener('click', this.onAbortButtonClick.bind(this))
  }

  protected initUserImageDeleteClick(): void {
    const checkboxElement = app.lightbox.content.querySelector<HTMLInputElement>('input#deleteLogo')

    if (!checkboxElement) {
      return
    }

    checkboxElement.addEventListener('change', this.onUserImageDeleteChange.bind(this))
  }

  protected onUserImageDeleteChange(): void {
    const formElement = app.lightbox.content.querySelector('form')
    const uploadElement = app.lightbox.content.querySelector('form input[name="tx_bwguild_api[user][logo]"]')

    if (!formElement || !uploadElement) {
      return
    }

    if (formElement.classList.contains('disabled-image-upload')) {
      uploadElement.removeAttribute('disabled')
      formElement.classList.remove('disabled-image-upload')
    } else {
      uploadElement.setAttribute('disabled', 'disabled')
      formElement.classList.add('disabled-image-upload')
    }
  }

  protected initUserRepresentativeSelect(): void {
    const selectElement = app.lightbox.content.querySelector('#user-committee')

    if (!selectElement) {
      return
    }

    selectElement.addEventListener('change', this.onUserRepresentativeSelectChange.bind(this))
  }

  protected onUserRepresentativeSelectChange(e: Event): void {
    e.preventDefault()
    const element = e.currentTarget as HTMLSelectElement
    const formElementDiv = element.closest('.form-element')
    formElementDiv?.classList.remove('active')
    if (parseInt(element.value) >= 0) {
      formElementDiv?.classList.add('active')
    }
  }

  protected initUserRepresentativeAutocomplete(): void {
    const inputElement = app.lightbox.content.querySelector<HTMLInputElement>('#representative')
    const hiddenElement = app.lightbox.content.querySelector<HTMLInputElement>('#representativeHiddenInput')

    if (!inputElement || !hiddenElement) {
      return
    }

    const allItems = JSON.parse(inputElement.getAttribute('data-autocomplete') ?? '') as AutocompleteItem[]

    autocomplete({
      input: inputElement,
      minLength: 2,
      disableAutoSelect: true,
      preventSubmit: true,
      fetch: this.onUserRepresentativeAutocompleteFetch.bind(this, allItems),
      onSelect: this.onUserRepresentativeAutocompleteSelect.bind(this, inputElement, hiddenElement)
    })
  }

  protected initClearLinks(): void {
    app.lightbox.content.querySelectorAll('a[data-clear-inputs]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault()
        const link = e.currentTarget as HTMLLinkElement
        app.lightbox.content.querySelectorAll<HTMLInputElement>(link.getAttribute('data-clear-inputs') ?? '').forEach(input => {
          input.value = ''
        })
      })
    })
  }

  protected initUserCommitteeRepresentativeAutocomplete(): void {
    const inputElement = app.lightbox.content.querySelector<HTMLInputElement>('#user-committee-representative')
    const hiddenElement = app.lightbox.content.querySelector<HTMLInputElement>('#committeeRepresentativeHiddenInput')

    if (inputElement === null || hiddenElement === null) {
      return
    }

    const allItems = JSON.parse(inputElement.getAttribute('data-autocomplete') ?? '') as AutocompleteItem[]

    autocomplete({
      input: inputElement,
      minLength: 2,
      disableAutoSelect: true,
      preventSubmit: true,
      fetch: this.onUserRepresentativeAutocompleteFetch.bind(this, allItems),
      onSelect: this.onUserRepresentativeAutocompleteSelect.bind(this, inputElement, hiddenElement)
    })
  }

  protected onUserRepresentativeAutocompleteFetch(allItems: AutocomleterItem[], text: string, update: any): void {
    text = text.toLowerCase()

    const filteredUsers = allItems
      .filter(item => {
        return item.label.toString().toLowerCase().includes(text)
      })
      .slice(0, 10)

    update(filteredUsers)
  }

  protected onUserRepresentativeAutocompleteSelect(input: HTMLInputElement, hiddenInput: HTMLInputElement, item: AutocomleterItem): void {
    input.value = item.label
    hiddenInput.value = item.value
  }

  protected initUserFeatureInputs(): void {
    const featureBubbleTemplate = document.querySelector('a[data-feature="###JS_TEMPLATE###"]')
    const selectElement = document.querySelector('select[name="tx_bwguild_api[user][features][]"]')

    app.lightbox.content.querySelectorAll('div[data-all-features]').forEach(container => {
      const inputElement = container.querySelector('input')
      const bubbleDropZoneElement = container.querySelector('ul.list')
      const recordType = container.getAttribute('data-record-type') ?? ''
      const allFeaturesJson = container.getAttribute('data-all-features') ?? ''
      const allFeatures = allFeaturesJson !== '' ? (JSON.parse(allFeaturesJson) as FeatureItem[]) : []
      const selectedFeaturesJson = container.getAttribute('data-selected-features') ?? ''
      let selectedFeatures = selectedFeaturesJson !== '' ? (JSON.parse(selectedFeaturesJson) as FeatureItem[]) : []

      if (!inputElement || !bubbleDropZoneElement) {
        return
      }

      function hashCode(str: string): string {
        let hash = 0
        for (let i = 0, len = str.length; i < len; i++) {
          const chr = str.charCodeAt(i)
          hash = (hash << 5) - hash + chr
          hash |= 0
        }
        return hash.toString()
      }

      function onBubbleClick(e: Event): void {
        e.preventDefault()

        if (!selectElement) {
          console.error('No element found', 1672608344)
          return
        }

        const featureElement = e.currentTarget as HTMLLinkElement
        const featureId = featureElement.getAttribute('data-feature') ?? ''
        // remove from selectable items
        selectedFeatures = selectedFeatures.filter(feature => feature.value !== featureId)

        // remove from form selection OR remove created newly created hidden element
        const isPersisted = parseInt(featureId) > 0
        if (isPersisted) {
          const optionElement = selectElement.querySelector('option[value="' + featureId + '"]')
          optionElement?.removeAttribute('selected')
        } else {
          container.querySelectorAll('input[name^="tx_bwguild_api[user][features][' + featureId + ']"]').forEach(el => {
            el.remove()
          })
        }
        // delete element
        featureElement.remove()
      }

      function addBubbleClickEvent(bubbleElement: Element): void {
        bubbleElement.addEventListener('click', onBubbleClick)
      }

      function addNewBubbleForFeature(feature: FeatureItem): void {
        if (!bubbleDropZoneElement || !featureBubbleTemplate) {
          return
        }
        const newFeatureBubble = featureBubbleTemplate.cloneNode(true) as HTMLLinkElement
        newFeatureBubble.setAttribute('data-feature', feature.value)
        const spanElement = newFeatureBubble.querySelector('span')
        if (spanElement) {
          spanElement.innerHTML = feature.label
        }
        newFeatureBubble.classList.remove('d-none')
        addBubbleClickEvent(newFeatureBubble)
        bubbleDropZoneElement.appendChild(newFeatureBubble)
      }

      function onAutocompleteSelect(item: FeatureItem): void {
        if (!inputElement || !selectElement) {
          return
        }
        // dynamic item (for new generation) was selected
        if (item.value === '') {
          onNewFeatureEntered()
          return
        }

        // create new bubble
        addNewBubbleForFeature(item)
        // add as selected in form
        selectElement.querySelector('option[value="' + item.value + '"]')?.setAttribute('selected', 'selected')
        // add to selected list
        selectedFeatures.push(item)
        // clear input
        inputElement.value = ''
      }

      function onAutocompleteFetch(text: string, update: any): void {
        let filteredFeatures = allFeatures.filter(feature => {
          const isMatchedByTextSearch = feature.label.toLowerCase().includes(text.toLowerCase())
          const isNotAlreadySelected = selectedFeatures.find(f => f.value === feature.value) === undefined
          return isMatchedByTextSearch && isNotAlreadySelected
        })

        // append new empty item
        if (allFeatures.filter((feature: FeatureItem) => feature.label.toLowerCase() === text.toLowerCase()).length !== 1) {
          filteredFeatures = [{ label: text, value: '' }, ...filteredFeatures]
        }

        update(filteredFeatures)
      }

      function onNewFeatureEntered(): void {
        if (!inputElement) {
          return
        }
        const trimmedInputString = inputElement.value.trim()
        const newFeatureId = 'NEW' + hashCode(trimmedInputString)
        const isAlreadySelected = selectedFeatures.find(f => f.label === trimmedInputString) !== undefined

        if (isAlreadySelected) {
          return
        }

        // create new feature
        const newFeatureItem: FeatureItem = { label: trimmedInputString, value: newFeatureId }
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

      bubbleDropZoneElement.querySelectorAll('a[data-feature]')?.forEach(bubbleElement => {
        addBubbleClickEvent(bubbleElement)
      })

      inputElement.addEventListener('keydown', e => {
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
        onSelect: onAutocompleteSelect,
        render: function (item: FeatureItem): HTMLDivElement | undefined {
          const itemElement = document.createElement('div')

          itemElement.innerHTML = item.label

          if (item.value === '') {
            itemElement.innerHTML += ' <span>neu hinzufügen</span>'
          }

          return itemElement
        }
      })
    })
  }

  protected onUserEditFormSubmit(e: Event): void {
    e.preventDefault()

    const form = e.currentTarget as HTMLFormElement
    const url = form.getAttribute('action') ?? ''
    const profileUrl = form.getAttribute('data-profile-url') ?? ''

    app.lightbox.startLoading()
    app
      .apiRequest(url, 'POST', form)
      .then(data => {
        localStorage.removeItem('userinfo')
        app.lightbox.displayContent(data.html)
        this.bindUserEditFormEvents()
        app.lightbox.stopLoading()
        // invalidate cache
        fetch(profileUrl, { cache: 'reload' }).then().catch()
        app.notice.open(NoticeStyle.success, 'Speichern erfolgreich', 2000)
      })
      .catch(() => app.handleRequestError.bind(this))
  }

  protected onAbortButtonClick(e: Event): void {
    e.preventDefault()
    app.lightbox.close()
  }
}

export default new Userprofile()
