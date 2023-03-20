import app from './basic'
import {LightboxStyle} from './lightbox'
import {NoticeStyle} from "./notice";

export interface UserData {
  uid: number
  username: string
  logo: string
  url: string
  first_name: string
  last_name: string
  email: string
}

export interface UserOffer {
  uid: number
  title: string
}

interface UserOffer {
  uid: number
  url: string
  title: string
  crdate: number
  categories: Category[]
}

interface UserBookmarks {
  fe_users: FeUserBookmark[]
  pages: PageBookmark[]
}

interface FeUserBookmark {
  first_name: string
  last_name: string
  uid: number
  username: string
}

interface PageBookmark {
  title: string
  uid: number
}

export interface UserinfoResponse {
  user: UserData
  offers: UserOffer[]
  bookmarks: UserBookmarks
  html: string
  validUntil: number
}

class Userinfo {
  protected userinfo: UserinfoResponse

  constructor() {
    this.bindStorageResetAtLogin()

    const hasUserinfoUri = document.querySelectorAll('#userinfoUri').length
    const isLightHouseRequest = navigator.userAgent.includes('Chrome-Lighthouse')
    if (!hasUserinfoUri || isLightHouseRequest) {
      return
    }

    this.loadUserinfo().then(() => {
      document.dispatchEvent(new CustomEvent('userinfo-update'))
    })

    this.bindEvents()
  }

  protected bindEvents(): void {
    // CustomEvent: userinfo was updated
    document.addEventListener('userinfo-update', this.onUserinfoUpdate.bind(this))
    // bookmark links
    document.querySelectorAll('button[data-bookmark-url]').forEach(button => {
      button.addEventListener('click', this.onBookmarkLinkClick.bind(this))
    })
    // sidebar: bookmark links
    document.querySelectorAll('.navigation__item--bookmark').forEach(link => {
      link.addEventListener('click', this.onBookmarkSidebarOpenClick.bind(this))
    })
    // create/edit offer button
    document.querySelectorAll('button[data-offer-edit-link]').forEach(btn => {
      btn.addEventListener('click', this.onEditOfferClick.bind(this, btn))
    })
    // delete offer
    document.querySelectorAll('button[data-offer-delete-link]').forEach(btn => {
      btn.addEventListener('click', this.onDeleteOfferClick.bind(this, btn))
    })
  }

  protected onUserinfoUpdate(): void {
    this.modifyShowForSelfClasses()
    this.modifyHtmlTag()
    this.modifyUserNav()
    this.modifyBookmarkLinks()
    this.modifyUserImagePreview()
    this.modifyWelcomeMessage()
    this.modifyMarketplace()
    this.modifyFeedbackForm()
  }

  protected bindStorageResetAtLogin(): void {
    const loginButton = document.querySelector('#login-link')
    if (loginButton) {
      loginButton.addEventListener('click', () => {
        localStorage.removeItem('userinfo')
      })
    }
    const logoutButton = document.querySelector('#logout-link')
    if (logoutButton) {
      logoutButton.addEventListener('click', () => {
        localStorage.removeItem('userinfo')
      })
    }
  }

  protected modifyShowForSelfClasses() {
    if (!this.userinfo) {
      return
    }

    document.querySelectorAll('.hide-for-self[data-user-uid="' + this.userinfo.user.uid + '"]').forEach(btn => {
      btn.classList.add('d-none')
    })

    document.querySelectorAll('.show-for-self[data-user-uid="' + this.userinfo.user.uid + '"]').forEach(btn => {
      btn.classList.remove('show-for-self')
    })
  }

  protected modifyHtmlTag() {
    if (this.userinfo) {
      document.querySelector('html')?.classList.add('loggedIn')
    }
  }

  protected onBookmarkLinkClick(e: Event) {
    e.preventDefault()

    if (!this.userinfo) {
      app.showLogin()
      return
    }

    const button = e.currentTarget as Element
    const url = button.getAttribute('data-bookmark-url') ?? ''
    const method = button.classList.contains('js--checked') ? 'DELETE' : 'POST'
    app.apiRequest(url, method).then(userinfo => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo))
      button.classList.toggle('fx--hover')
      button.classList.toggle('js--checked')
    })

    if (method === 'POST') {
      const topbarButton = document.querySelector<HTMLButtonElement>('.navigation__item--bookmark')
      topbarButton?.classList.add('animation')
      setTimeout(() => topbarButton?.classList.remove('animation'), 1000)
    }
  }

  protected onBookmarkSidebarOpenClick() {
    if (!this.userinfo) {
      app.showLogin()
      return
    }

    this.modifyBookmarkSidebar()
  }

  protected modifyBookmarkSidebar() {
    if (!this.userinfo) {
      return
    }

    app.lightbox.displayContent(this.userinfo.html)
    app.lightbox.content.querySelectorAll('a[data-bookmark-url]').forEach(link => {
      link.addEventListener('click', this.onBookmarkSidebarLinkClick.bind(this))
    })
    app.lightbox.stopLoading()
    app.lightbox.open(LightboxStyle.sidebar)
  }

  protected onBookmarkSidebarLinkClick(e: Event) {
    e.preventDefault()
    const link = e.currentTarget as HTMLLinkElement
    const url = link.getAttribute('data-bookmark-url') ?? ''
    app.lightbox.startLoading()
    app.apiRequest(url, 'DELETE').then(userinfo => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo))
      this.modifyBookmarkLinks()
      app.lightbox.displayContent(userinfo.html)
      app.lightbox.content.querySelectorAll('a[data-bookmark-url]').forEach(link => {
        link.addEventListener('click', this.onBookmarkSidebarLinkClick.bind(this))
      })
      app.lightbox.stopLoading()
    })
  }

  protected modifyUserNav() {
    const userLinkElement = document.querySelector('[data-user-profile-link]')
    if (!userLinkElement || !this.userinfo) {
      return
    }
    userLinkElement.setAttribute('href', this.userinfo.user.url)
  }

  protected modifyWelcomeMessage() {
    const welcomeMessageBox = document.querySelector('.employee-welcome')
    const usernameElement = document.querySelector('.employee-welcome span[data-username]')
    if (!welcomeMessageBox || !usernameElement || !this.userinfo) {
      return
    }
    usernameElement.innerHTML = this.userinfo.user.first_name + ' ' + this.userinfo.user.last_name
    welcomeMessageBox.classList.remove('employee-welcome--onload-hidden')
  }

  protected modifyMarketplace(): void {
    const marketPlace = document.querySelector('#my-marketplace')
    const orderDummy = document.querySelector('#my-marketplace-dummy')
    if (!marketPlace || !orderDummy || !this.userinfo || !this.userinfo.offers.length) {
      return
    }

    marketPlace.innerHTML = ''

    this.userinfo.offers.forEach(order => {
      const orderBox = orderDummy.cloneNode(true) as HTMLLinkElement
      orderBox.querySelector('.orders__link').setAttribute('href', order.url)
      orderBox.querySelector('.orders__link').setAttribute('title', order.title)
      orderBox.querySelector('h5').innerHTML = order.title
      orderBox.querySelector('.orders__date').innerHTML = new Date(order.crdate * 1000).toDateString()
      orderBox.querySelector('p').innerHTML = order.categories[0]?.title ?? ''
      const deleteUrl = orderBox.querySelector('button[data-offer-delete-link]').getAttribute('data-offer-delete-link')
      const editUrl = orderBox.querySelector('button[data-offer-edit-link]').getAttribute('data-offer-edit-link')
      orderBox
        .querySelector('button[data-offer-delete-link]')
        .setAttribute('data-offer-delete-link', deleteUrl.replace('_uid_', order.uid.toString()))
      orderBox
        .querySelector('button[data-offer-edit-link]')
        .setAttribute('data-offer-edit-link', editUrl.replace('_uid_', order.uid.toString()))
      orderBox.classList.remove('d-none')
      marketPlace.append(orderBox)
    })

    this.bindEvents()
  }

  protected modifyBookmarkLinks() {
    if (!this.userinfo) {
      return
    }

    document.querySelectorAll('button[data-bookmark-url]').forEach(button => {
      button.classList.remove('fx--hover', 'js--checked')
      const urlParts = button.getAttribute('data-bookmark-url')?.match('(?:bookmark\\/)([\\w\\d]+)(?:\\/)(\\d+)(?:\\.json)')
      if (!urlParts || urlParts.length !== 3) {
        return
      }
      if (!(urlParts[1] in this.userinfo.bookmarks)) {
        return
      }
      // @ts-expect-error
      if (!(urlParts[2] in this.userinfo.bookmarks[urlParts[1]])) {
        return
      }
      button.classList.add('fx--hover', 'js--checked')
    })
  }

  protected modifyUserImagePreview(): void {
    const figure = document.querySelector('#user-image-preview')
    if (!this.userinfo?.user?.logo || !figure) {
      return
    }
    const image = document.createElement('img')
    image.setAttribute('src', this.userinfo.user.logo)
    figure.replaceChild(image, figure.childNodes.item(1))
    figure.closest('button')?.classList.add('user-image-loaded')
  }

  protected modifyFeedbackForm() {
    const nameField = document.getElementById('generalfeedbackform-name') as HTMLInputElement
    const emailField = document.getElementById('generalfeedbackform-email') as HTMLInputElement

    if (nameField && emailField && this.userinfo) {
      if (this.userinfo.user.first_name && this.userinfo.user.last_name) {
        nameField.value = this.userinfo.user.first_name + ' ' + this.userinfo.user.last_name
      } else {
        nameField.value = this.userinfo.user.username
      }
      emailField.value = this.userinfo.user.email
    }
  }

  protected async loadUserinfo() {
    const loadedFromStorage = this.loadUserinfoFromStorage()
    if (!loadedFromStorage) {
      await this.loadUserinfoFromApi()
      return
    }
  }

  protected loadUserinfoFromStorage(): boolean {
    const storedUserinfo = localStorage.getItem('userinfo')
    if (!storedUserinfo) {
      return false
    }
    try {
      const userInfo: UserinfoResponse = JSON.parse(storedUserinfo)

      if (new Date(userInfo.validUntil * 1000) < new Date()) {
        localStorage.removeItem('userinfo')
        return false
      }

      this.userinfo = userInfo
    } catch (e) {
      return false
    }

    return true
  }

  protected async loadUserinfoFromApi() {
    const url = document.querySelector('#userinfoUri')?.getAttribute('data-user-info') ?? ''

    if (!url) {
      return
    }

    await app.apiRequest(url).then(userinfo => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo))
    })
  }

  protected onEditOfferClick(button: HTMLButtonElement, e: Event): void {
    e.preventDefault()
    const url = button.getAttribute('data-offer-edit-link')

    app.lightbox.startLoading()
    app.lightbox.open()
    app
      .apiRequest(url)
      .then(data => data.html)
      .then(formHtml => {
        app.lightbox.displayContent(formHtml)
        this.onOfferFormLoaded()
        app.lightbox.stopLoading()
      })
      .catch(() => {
        app.notice.open(NoticeStyle.error, 'Could not load form, please reload and try again.')
      })
  }

  protected onDeleteOfferClick(button: HTMLButtonElement, e: Event): void {
    e.preventDefault()
    const url = button.getAttribute('data-offer-delete-link')

    app
      .apiRequest(url)
      .then(() => {
        app.notice.open(NoticeStyle.success, 'Offer deleted', 1000)
        button.closest('.orders__item').remove()
      })
      .catch(() => {
        app.notice.open(NoticeStyle.error, 'Could not delete item, please reload and try again.')
      })
  }

  protected onOfferFormLoaded(): void {
    const form = app.lightbox.content.querySelector('form')
    form.addEventListener('submit', this.onOfferFormSubmit.bind(this))
  }

  protected onOfferFormSubmit(e: SubmitEvent): void {
    e.preventDefault()
    const form = e.currentTarget as HTMLFormElement
    const url = form.getAttribute('action') ?? ''

    app.lightbox.startLoading()
    app
      .apiRequest(url, 'POST', form)
      .then(data => {
        app.lightbox.displayContent(data.html)
        localStorage.setItem('userinfo', JSON.stringify(data.userinfo))
        this.userinfo = data.userinfo
        this.onOfferFormLoaded()
        this.modifyMarketplace()
        app.lightbox.stopLoading()
        app.notice.open(NoticeStyle.success, 'Speichern erfolgreich', 2000)
      })
      .catch(() => app.handleRequestError.bind(this))
  }
}

export default new Userinfo()
