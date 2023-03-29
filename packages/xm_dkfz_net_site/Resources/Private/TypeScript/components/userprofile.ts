import app from './basic'
import autocomplete, { AutocompleteItem } from 'autocompleter'
import { AutocomleterItem } from './hero-form'
import { NoticeStyle } from './notice'
import ImageEditor from './image-editor'
import Lightbox from './lightbox'

interface FeatureItem extends AutocompleteItem {
  label: string
  value: string
}

class Userprofile {
  protected profileEditLightbox: Lightbox
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
    this.profileEditLightbox = new Lightbox()
    this.profileEditLightbox.preserveContent = true

    this.profileEditLightbox.startLoading()
    this.profileEditLightbox.open()
    this.loadUserEditForm(url)
      .then(formHtml => {
        this.profileEditLightbox.displayContent(formHtml)
        this.bindUserEditFormEvents()
        this.profileEditLightbox.stopLoading()
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
    const form = this.profileEditLightbox.content.querySelector('form')
    const logoUploadInput = this.profileEditLightbox.content.querySelector<HTMLInputElement>('input[name="tx_bwguild_api[user][logo]"]')
    this.initImageEditButtonClick()
    this.initUserImageDeleteClick()
    this.initUserRepresentativeSelect()
    this.initUserRepresentativeAutocompleter()
    this.initUserFeatureInputs()
    this.initClearLinks()
    form?.addEventListener('submit', this.onUserEditFormSubmit.bind(this))
    form?.querySelector('button[data-abort]')?.addEventListener('click', this.onAbortButtonClick.bind(this))

    logoUploadInput?.addEventListener('change', this.onLogoUploadChange.bind(this, logoUploadInput))
  }

  protected onLogoUploadChange(logoUploadInput: HTMLInputElement): void {
    const hiddenCropInput = document.querySelector<HTMLInputElement>('input[name="tx_bwguild_api[user][logo][crop]"]')
    if (hiddenCropInput) {
      hiddenCropInput.value = ''
    }
    this.createImageEditor(logoUploadInput)
  }

  protected createImageEditor(logoUploadInput: any): void {
    let file: File | undefined = logoUploadInput.files?.[0]
    const userImagePicture: HTMLPictureElement | null = this.profileEditLightbox.content.querySelector('.userimage picture')
    const cropAreaInput: HTMLInputElement | null = this.profileEditLightbox.content.querySelector<HTMLInputElement>(
      'input[name="tx_bwguild_api[user][logo][crop]"]'
    )
    const cropArea = cropAreaInput?.value ? JSON.parse(cropAreaInput.value) : null

    if (!file) {
      file = logoUploadInput.getAttribute('data-original')
    }

    if (!file || !userImagePicture) {
      return
    }

    const imageEditor = new ImageEditor(cropArea, 'square')

    imageEditor.show(file)

    imageEditor.lightbox.box.addEventListener('imagecrop', (e: CustomEvent) => {
      this.replaceOriginalImage(e.detail.previewImage, userImagePicture)
      this.showImageEditButton()
      this.setImageCropArea(cropAreaInput, JSON.stringify(e.detail.cropArea))
    })
  }

  protected setImageCropArea(cropAreaInput: HTMLInputElement | null, cropAreaValue: string): void {
    if (cropAreaInput) {
      cropAreaInput.value = cropAreaValue
    }
  }

  protected replaceOriginalImage(previewImageSource: string, previewImageTarget: HTMLPictureElement): void {
    const previewImage = new Image()
    previewImage.src = previewImageSource

    previewImage.width = 150
    previewImage.height = 150

    previewImageTarget?.querySelector('svg')?.remove()
    previewImageTarget?.querySelector('img')?.remove()
    previewImageTarget?.prepend(previewImage)
  }

  protected showImageEditButton(): void {
    const imageEditButton = this.profileEditLightbox.content.querySelector('.userimage .userimage__edit-button')
    imageEditButton?.classList.remove('userimage__edit-button--hidden')
  }

  protected initImageEditButtonClick(): void {
    const userProfileImageEditButton = this.profileEditLightbox.content.querySelector('.userimage .userimage__edit-button')

    if (userProfileImageEditButton) {
      userProfileImageEditButton.addEventListener('click', () => {
        const logoUploadInput = this.profileEditLightbox.content.querySelector<HTMLInputElement>('input[name="tx_bwguild_api[user][logo]"]')
        this.createImageEditor(logoUploadInput)
      })
    }
  }

  protected initUserImageDeleteClick(): void {
    const checkboxElement = this.profileEditLightbox.content.querySelector<HTMLInputElement>('input#deleteLogo')

    if (!checkboxElement) {
      return
    }

    checkboxElement.addEventListener('change', this.onUserImageDeleteChange.bind(this))
  }

  protected onUserImageDeleteChange(): void {
    const formElement = this.profileEditLightbox.content.querySelector('form')
    const uploadElement = this.profileEditLightbox.content.querySelector('form input[name="tx_bwguild_api[user][logo]"]')

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
    const selectElement = this.profileEditLightbox.content.querySelector('#user-committee')

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

  protected initUserRepresentativeAutocompleter(): void {
    const inputSelectors = [
      ['#representative', '#representativeHiddenInput'],
      ['#representative2', '#representative2HiddenInput'],
      ['#user-committee-representative', '#committeeRepresentativeHiddenInput'],
      ['#user-committee-representative2', '#committeeRepresentative2HiddenInput']
    ]

    inputSelectors.forEach(selectors => {
      const inputElement = this.profileEditLightbox.content.querySelector<HTMLInputElement>(selectors[0])
      const hiddenElement = this.profileEditLightbox.content.querySelector<HTMLInputElement>(selectors[1])

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
    })
  }

  protected initClearLinks(): void {
    this.profileEditLightbox.content.querySelectorAll('a[data-clear-inputs]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault()
        const link = e.currentTarget as HTMLLinkElement
        let newValue = ''
        let newHiddenValue = ''

        if (link.hasAttribute('data-hide-onclear')) {
          const element = this.profileEditLightbox.content.querySelector<HTMLDivElement>(link.getAttribute('data-hide-onclear') ?? '')
          newValue = element?.querySelector<HTMLInputElement>('input[type="text"]')?.value ?? ''
          newHiddenValue = element?.querySelector<HTMLInputElement>('input[type="hidden"]')?.value ?? ''
          element?.querySelector<HTMLAnchorElement>('a[data-clear-inputs]')?.click()
          if (!newValue) {
            element?.classList.add('d-none')
          }
        }

        this.profileEditLightbox.content.querySelectorAll<HTMLInputElement>(link.getAttribute('data-clear-inputs') ?? '').forEach(input => {
          if (input.getAttribute('type') === 'hidden') {
            input.value = newHiddenValue
          } else {
            input.value = newValue
          }
        })
      })
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
    if (input.hasAttribute('data-show-oninput')) {
      document.querySelector<HTMLDivElement>(input.getAttribute('data-show-oninput') ?? '')?.classList.remove('d-none')
    }
  }

  protected initUserFeatureInputs(): void {
    const featureBubbleTemplate = document.querySelector('a[data-feature="###JS_TEMPLATE###"]')
    const selectElement = document.querySelector('select[name="tx_bwguild_api[user][features][]"]')

    this.profileEditLightbox.content.querySelectorAll('div[data-all-features]').forEach(container => {
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

    this.profileEditLightbox.startLoading()
    app
      .apiRequest(url, 'POST', form)
      .then(data => {
        localStorage.removeItem('userinfo')
        this.profileEditLightbox.displayContent(data.html)
        this.bindUserEditFormEvents()
        this.profileEditLightbox.stopLoading()
        // invalidate cache
        fetch(profileUrl, { cache: 'reload' }).then().catch()
        app.notice.open(NoticeStyle.success, 'Speichern erfolgreich', 2000)
      })
      .catch(() => app.handleRequestError.bind(this))
  }

  protected onAbortButtonClick(e: Event): void {
    e.preventDefault()
    this.profileEditLightbox.close()
  }
}

export default new Userprofile()
