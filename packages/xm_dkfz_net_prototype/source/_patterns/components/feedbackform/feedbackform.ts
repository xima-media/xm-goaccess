import app from '../basic/basic'
import './feedbackform.scss'

class Feedbackform {

  constructor() {
    app.log('component "feedbackform" loaded')

    this.bindEvents()
  }

  protected bindEvents() {
    this.bindFeedbackLink()
  }

  protected bindFeedbackLink() {
    document.querySelectorAll('a[href="#message"]').forEach((link) => {
      link.addEventListener('click', this.onFeedbackLinkClick.bind(this))
    });
  }

  protected onFeedbackLinkClick(e: Event) {
    e.preventDefault()
    const form = document.getElementById('generalfeedbackform') as HTMLElement

    if (form) {
      let displayContent = form.outerHTML
      const email = form.querySelector('#generalfeedbackform-email') as HTMLInputElement

      if (email.value.length === 0) {
        if(document.documentElement.getAttribute('lang') === 'de-de') {
          displayContent = '<p>Sie müssen eingeloggt sein, um Feedback zu senden!</p>'
        } else {
          displayContent = '<p>You have to be logged in to send feedback!</p>'
        }
      }

      app.lightbox.startLoading()
      app.lightbox.open()
      app.lightbox.displayContent(displayContent)
      this.bindFeedbackFormEvents()
      app.lightbox.stopLoading()
    }
  }

  protected bindFeedbackFormEvents() {
    const form = app.lightbox.content.querySelector('form')
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

      if(!formElement.value) {
        formGroup.classList.remove('fx--focus')
      }
    })
  }

  protected toggleLabelByFocus(formElement: HTMLInputElement|HTMLTextAreaElement, formGroup: HTMLDivElement) {
    formElement.addEventListener('focus', () => {
      formGroup.classList.add('fx--focus')
    })

    formElement.addEventListener('focusout', () => {
      if(!formElement.value) {
        formGroup.classList.remove('fx--focus')
      }
    })
  }

  protected onFeedbackFormSubmit(e: Event) {
    e.preventDefault()

    const form = e.currentTarget as HTMLFormElement
    const submitButton = form.querySelector('button[type="submit"]') as HTMLButtonElement
    let formData = new FormData(form)
    formData.append(submitButton.name, submitButton.value)
    const requestInit = {
      method: 'POST',
      body: formData
    }

    app.lightbox.startLoading()
    app.lightbox.clear()
    fetch(form.action, requestInit)
      .then((response) => {
        if (!response.ok) {
          console.error('Submitting feedback failed', response)
        }
        return response.text()
      })
      .then((html) => {
        let doc = document.createRange().createContextualFragment(html);
        const feedbackform = doc.querySelector('#generalfeedbackform')
        app.lightbox.displayContent(feedbackform.outerHTML)
        this.bindFeedbackFormEvents()
        app.lightbox.stopLoading()
      })
      .catch(error => {
        console.error('Submitting feedback failed', error)
      })
  }
}

export default (new Feedbackform())
