import app from './basic'
import Lightbox from './lightbox'

class Feedbackform {
  protected feedbackFormLightbox: Lightbox
  constructor() {
    this.bindEvents()
  }

  protected bindEvents() {
    this.bindFeedbackLink()
  }

  protected bindFeedbackLink() {
    document.querySelectorAll('a[href="#message"]').forEach(link => {
      link.addEventListener('click', this.onFeedbackLinkClick.bind(this))
    })
  }

  protected onFeedbackLinkClick(e: Event) {
    e.preventDefault()
    const form = document.getElementById('generalfeedbackform')

    if (form) {
      let displayContent = form.outerHTML
      const email = form.querySelector<HTMLInputElement>('#generalfeedbackform-email')

      if (email && email.value.length === 0) {
        if (document.documentElement.getAttribute('lang') === 'de-de') {
          displayContent = '<p>Sie müssen eingeloggt sein, um Feedback zu senden!</p>'
        } else {
          displayContent = '<p>You have to be logged in to send feedback!</p>'
        }
      }

      this.feedbackFormLightbox = new Lightbox()
      this.feedbackFormLightbox.startLoading()
      this.feedbackFormLightbox.open()
      this.feedbackFormLightbox.displayContent(displayContent)
      this.bindFeedbackFormEvents()
      this.feedbackFormLightbox.stopLoading()
    }
  }

  protected bindFeedbackFormEvents() {
    const form = this.feedbackFormLightbox.content.querySelector('form')
    if (form) {
      form.addEventListener('submit', this.onFeedbackFormSubmit.bind(this))

      const formGroups = document.querySelectorAll('.form-group')

      formGroups.forEach((formGroup: HTMLDivElement) => {
        const select = formGroup.querySelector('select')
        const textArea = formGroup.querySelector('textarea')

        if (select) {
          this.toggleLabelByChange(select, formGroup)
        }

        if (textArea) {
          this.toggleLabelByFocus(textArea, formGroup)
        }
      })
    }
  }

  protected toggleLabelByChange(formElement: HTMLSelectElement, formGroup: HTMLDivElement) {
    formElement.addEventListener('change', () => {
      formGroup.classList.add('fx--focus')

      if (!formElement.value) {
        formGroup.classList.remove('fx--focus')
      }
    })
  }

  protected toggleLabelByFocus(formElement: HTMLInputElement | HTMLTextAreaElement, formGroup: HTMLDivElement) {
    formElement.addEventListener('focus', () => {
      formGroup.classList.add('fx--focus')
    })

    formElement.addEventListener('focusout', () => {
      if (!formElement.value) {
        formGroup.classList.remove('fx--focus')
      }
    })
  }

  protected onFeedbackFormSubmit(e: Event): void {
    e.preventDefault()

    const form = e.currentTarget as HTMLFormElement
    const submitButton = form.querySelector<HTMLButtonElement>('button[type="submit"]')
    const formData = new FormData(form)

    if (!submitButton) {
      console.error('Submit button not found', 1672609585)
      return
    }

    formData.append(submitButton.name, submitButton.value)
    const requestInit = {
      method: 'POST',
      body: formData
    }

    this.feedbackFormLightbox.startLoading()
    this.feedbackFormLightbox.clear()
    fetch(form.action, requestInit)
      .then(async response => {
        if (!response.ok) {
          console.error('Submitting feedback failed', response)
        }
        return await response.text()
      })
      .then(html => {
        const doc = document.createRange().createContextualFragment(html)
        const feedbackform = doc.querySelector('#generalfeedbackform')
        if (!feedbackform) {
          console.error('Could not find feedback form', 1672604140)
          return
        }
        this.feedbackFormLightbox.displayContent(feedbackform.outerHTML)
        this.bindFeedbackFormEvents()
        this.feedbackFormLightbox.stopLoading()
      })
      .catch(error => {
        console.error('Submitting feedback failed', error)
      })
  }
}

export default new Feedbackform()
