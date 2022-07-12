import app from '../basic/basic'
import './userprofile.scss'

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
    form.addEventListener('submit', this.onUserEditFormSubmit.bind(this))
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
