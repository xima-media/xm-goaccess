import app from '../basic/basic'
import './userprofile.scss'

class Userprofile {

  protected lightbox: Element;

  constructor() {
    app.log('component "userinfo" loaded')

    this.cacheDom()
    this.bindEvents()
}

  protected cacheDom() {
    this.lightbox = document.querySelector('.lightbox')
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
      this.bindUserEditFormEvents()
      app.lightbox.displayContent(formHtml)
      app.lightbox.stopLoading()
    })
  }

  protected async loadUserEditForm(url: string) {
    return app.apiRequest(url).then(data => {
      return data.html
    })
  }

  protected bindUserEditFormEvents() {
  }


}

export default (new Userprofile())
