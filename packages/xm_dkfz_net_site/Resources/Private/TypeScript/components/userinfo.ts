import app from './basic'
import { LightboxStyle } from './lightbox'

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

export interface UserBookmarks {
  fe_users: object
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

  constructor () {
    this.bindStorageResetAtLogin()

    const hasUserinfoUri = document.querySelectorAll('#userinfoUri').length
    const isLightHouseRequest = navigator.userAgent.indexOf("Chrome-Lighthouse") > -1
    if (!hasUserinfoUri || isLightHouseRequest) {
      return
    }

    this.loadUserinfo().then(() => {
      this.modifyHtmlTag()
      this.modifyUserNav()
      this.modifyBookmarkLinks()
      this.modifyWelcomeMessage()
      this.setFeedbackFormUserValues()
    })

    this.bindEvents()
  }

  protected bindEvents () {
    this.bindBookmarkLinks()
    this.bindBookmarkSidebar()
  }

  protected bindBookmarkLinks () {
    document.querySelectorAll('button[data-bookmark-url]').forEach((button) => {
      button.addEventListener('click', this.onBookmarkLinkClick.bind(this))
    })
  }

  protected bindBookmarkSidebar () {
    document.querySelectorAll('.navigation__item--bookmark').forEach(link => {
      link.addEventListener('click', this.onBookmarkSidebarOpenClick.bind(this))
    })
  }

  protected bindStorageResetAtLogin () {
    const loginButton = document.querySelector('#login-link')
    if (loginButton) {
      loginButton.addEventListener('click', () => {
        localStorage.removeItem('userinfo')
      })
    }
  }

  protected modifyHtmlTag() {
    if (this.userinfo) {
      document.querySelector('html').classList.add('loggedIn')
    }
  }

  protected onBookmarkLinkClick (e: Event) {
    e.preventDefault()

    if (!this.userinfo) {
      app.showLogin()
      return
    }

    const button = e.currentTarget as Element
    const url = button.getAttribute('data-bookmark-url')
    const method = button.classList.contains('js--checked') ? 'DELETE' : 'POST'
    app.apiRequest(url, method).then((userinfo) => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo))
      button.classList.toggle('fx--hover')
      button.classList.toggle('js--checked')
    })
  }

  protected onBookmarkSidebarOpenClick () {
    if (!this.userinfo) {
      app.showLogin()
      return
    }

    this.modifyBookmarkSidebar()
  }

  protected modifyBookmarkSidebar () {
    if (!this.userinfo) {
      return
    }

    app.lightbox.displayContent(this.userinfo.html)
    app.lightbox.stopLoading()
    app.lightbox.open(LightboxStyle.sidebar)
  }

  protected modifyUserNav () {
    const userLinkElement = document.querySelector('[data-user-profile-link]')
    if (!userLinkElement || !this.userinfo) {
      return
    }
    userLinkElement.setAttribute('href', this.userinfo.user.url)
  }

  protected modifyWelcomeMessage () {
    const welcomeMessageBox = document.querySelector('.employee-welcome')
    if (!welcomeMessageBox || !this.userinfo) {
      return
    }
    welcomeMessageBox.querySelector('span[data-username]').innerHTML = this.userinfo.user.username
    welcomeMessageBox.classList.remove('employee-welcome--onload-hidden')
  }

  protected modifyBookmarkLinks () {
    if (!this.userinfo) {
      return
    }

    document.querySelectorAll('button[data-bookmark-url]').forEach((button) => {
      const urlParts = button.getAttribute('data-bookmark-url').match('(?:bookmark\\/)([\\w\\d]+)(?:\\/)(\\d+)(?:\\.json)')
      if (urlParts.length !== 3) {
        return
      }
      if (!(urlParts[1] in this.userinfo.bookmarks)) {
        return
      }
      // @ts-ignore
      if (!(urlParts[2] in this.userinfo.bookmarks[urlParts[1]])) {
        return
      }
      button.classList.add('fx--hover', 'js--checked')
    })
  }

  protected setFeedbackFormUserValues () {
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

  protected async loadUserinfo () {
    const loadedFromStorage = this.loadUserinfoFromStorage()
    if (!loadedFromStorage) {
      return await this.loadUserinfoFromApi()
    }
  }

  protected loadUserinfoFromStorage (): boolean {
    const storedUserinfo = localStorage.getItem('userinfo')
    if (!storedUserinfo) {
      return false
    }
    try {
      const userInfo: UserinfoResponse = JSON.parse(storedUserinfo)

      if (new Date(userInfo.validUntil * 1000) > new Date()) {
        return false
      }

      this.userinfo = userInfo
    } catch (e) {
      return false
    }

    return true
  }

  protected async loadUserinfoFromApi () {
    const url = document.querySelector('#userinfoUri').getAttribute('data-user-info')

    if (!url) {
      return
    }

    return await app.apiRequest(url).then((userinfo) => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo))
    })
  }
}

export default (new Userinfo())
