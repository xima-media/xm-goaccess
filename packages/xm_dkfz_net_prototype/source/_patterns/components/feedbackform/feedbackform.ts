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

    app.lightbox.startLoading()
    app.lightbox.open()

    let formHtml = document.getElementById('generalfeedbackform') as HTMLElement

    app.lightbox.displayContent(formHtml.outerHTML)
    app.lightbox.stopLoading()
  }
}

export default (new Feedbackform())
