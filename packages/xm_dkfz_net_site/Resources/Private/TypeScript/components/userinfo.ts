import app from './basic'
import {LightboxStyle} from './lightbox'
import {NoticeStyle} from './notice'
import Lightbox from './lightbox'
import {v} from "npm-check-updates/build/src/lib/version-util";

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

export interface Category {
  uid: number
  title: string
}

export interface UserOffer {
  uid: number
  url: string
  title: string
  crdate: number
  categories: Category[]
  public: boolean
}

export interface UserBookmarks {
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
  protected userInfoLightbox: Lightbox
  protected offerLightbox: Lightbox

  constructor() {
    this.bindStorageResetAtLogin()

    const hasUserinfoUri = document.querySelectorAll('#userinfoUri').length
    const isLightHouseRequest = navigator.userAgent.includes('Chrome-Lighthouse')
    if (!hasUserinfoUri || isLightHouseRequest) {
      return
    }

    this.loadUserinfo()
      .then(() => document.dispatchEvent(new CustomEvent('userinfo-update')))
      .catch(() => {
        app.notice.open(NoticeStyle.warning, 'Could not load user data', 2000)
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
    // my offer link
    document.querySelectorAll('a[data-marketplace-link]').forEach(link => {
      link.addEventListener('click', this.onMarketplaceLinkClick.bind(this, link))
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

  protected modifyShowForSelfClasses(): void {
    if (!this.userinfo) {
      return
    }

    document.querySelectorAll(`.hide-for-self[data-user-uid="${this.userinfo.user.uid}"]`).forEach(btn => {
      btn.classList.add('d-none')
    })

    document.querySelectorAll(`.show-for-self[data-user-uid="${this.userinfo.user.uid}"]`).forEach(btn => {
      btn.classList.remove('show-for-self')
    })
  }

  protected modifyHtmlTag(): void {
    if (this.userinfo) {
      document.querySelector('html')?.classList.add('loggedIn')
    }
  }

  protected onBookmarkLinkClick(e: Event): void {
    e.preventDefault()

    if (!this.userinfo) {
      app.showLogin()
      return
    }

    const button = e.currentTarget as Element
    const url = button.getAttribute('data-bookmark-url') ?? ''
    const method = button.classList.contains('js--checked') ? 'DELETE' : 'POST'
    app
      .apiRequest(url, method)
      .then(userinfo => {
        this.userinfo = userinfo
        localStorage.setItem('userinfo', JSON.stringify(userinfo))
        button.classList.toggle('fx--hover')
        button.classList.toggle('js--checked')
      })
      .catch(() => {
        app.notice.open(NoticeStyle.error, 'Error saving bookmark', 2000)
      })

    if (method === 'POST') {
      const topbarButton = document.querySelector<HTMLButtonElement>('.navigation__item--bookmark')
      topbarButton?.classList.add('animation')
      setTimeout(() => topbarButton?.classList.remove('animation'), 1000)
    }
  }

  protected onBookmarkSidebarOpenClick(): void {
    if (!this.userinfo) {
      app.showLogin()
      return
    }

    this.modifyBookmarkSidebar()
  }

  protected modifyBookmarkSidebar(): void {
    if (!this.userinfo) {
      return
    }

    this.userInfoLightbox = new Lightbox()
    this.userInfoLightbox.displayContent(this.userinfo.html)
    this.userInfoLightbox.content.querySelectorAll('a[data-bookmark-url]').forEach(link => {
      link.addEventListener('click', this.onBookmarkSidebarLinkClick.bind(this))
    })
    this.userInfoLightbox.stopLoading()
    this.userInfoLightbox.open(LightboxStyle.sidebar)
  }

  protected onBookmarkSidebarLinkClick(e: Event): void {
    e.preventDefault()
    const link = e.currentTarget as HTMLLinkElement
    const url = link.getAttribute('data-bookmark-url') ?? ''
    this.userInfoLightbox.startLoading()
    void app.apiRequest(url, 'DELETE').then(userinfo => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo))
      this.modifyBookmarkLinks()
      this.userInfoLightbox.displayContent(userinfo.html)
      this.userInfoLightbox.content.querySelectorAll('a[data-bookmark-url]').forEach(link => {
        link.addEventListener('click', this.onBookmarkSidebarLinkClick.bind(this))
      })
      this.userInfoLightbox.stopLoading()
    })
  }

  protected modifyUserNav(): void {
    const userLinkElement = document.querySelector('[data-user-profile-link]')
    if (!userLinkElement || !this.userinfo) {
      return
    }
    userLinkElement.setAttribute('href', this.userinfo.user.url)
  }

  protected modifyWelcomeMessage(): void {
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
    if (!marketPlace || !orderDummy) {
      return
    }

    if (!this.userinfo) {
      document.querySelector('a[data-marketplace="my"]').addEventListener('click', e => {
        e.preventDefault()
        app.showLogin()
      })
      return
    }

    if (!this.userinfo.offers) {
      return
    }

    // marketplace navigation @TODO: move to better location
    document.querySelectorAll('a[data-marketplace]').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault()
        const currentLink = e.currentTarget as HTMLLinkElement
        document.querySelectorAll('a[data-marketplace]').forEach(link => link.classList.remove('active'))
        currentLink.classList.add('active')
        document.querySelectorAll('.marketplace-list').forEach(div => div.classList.remove('active'))
        document.querySelector(`.marketplace-list[data-marketplace="${link.getAttribute('data-marketplace')}"]`).classList.add('active')

        // persist view
        const currentView = currentLink.getAttribute('data-marketplace') ?? ''
        localStorage.setItem('marketplace-view', currentView)
      })
    })

    marketPlace.innerHTML = ''

    this.userinfo.offers.reverse().forEach(order => {
      const orderBox = orderDummy.cloneNode(true) as HTMLLinkElement
      orderBox.querySelector('.orders__link')?.setAttribute('href', order.url)
      orderBox.querySelector('.orders__link')?.setAttribute('title', order.title)
      // @ts-expect-error
      orderBox.querySelector('h5').innerHTML = order.title
      // @ts-expect-error
      orderBox.querySelector('h5').setAttribute('data-record-type', order.record_type)
      // @ts-expect-error
      orderBox.querySelector('.orders__date').innerHTML = new Date(order.crdate * 1000).toLocaleDateString('de-DE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      })
      // @ts-expect-error
      orderBox.querySelector('p').innerHTML = order.categories[0]?.title ?? ''
      // @ts-expect-error
      orderBox.querySelector('span[data-public]').setAttribute('data-public', order.public ? '1' : '0')
      // delete button
      const deleteUrl = orderBox.querySelector('button[data-offer-delete-link]')?.getAttribute('data-offer-delete-link') ?? ''
      const deleteLink = orderBox.querySelector('button[data-offer-delete-link]')
      deleteLink?.setAttribute('data-offer-delete-link', deleteUrl.replace('_uid_', order.uid.toString()))
      deleteLink?.addEventListener('click', this.onDeleteOfferClick.bind(this, deleteLink))
      // edit button
      const editUrl = orderBox.querySelector('button[data-offer-edit-link]')?.getAttribute('data-offer-edit-link') ?? ''
      const editLink = orderBox.querySelector('button[data-offer-edit-link]')
      editLink?.setAttribute('data-offer-edit-link', editUrl.replace('_uid_', order.uid.toString()))
      editLink?.addEventListener('click', this.onEditOfferClick.bind(this, editLink))
      // show & append
      orderBox.classList.remove('d-none')
      marketPlace.append(orderBox)
    })

    // onload: change view
    const view = localStorage.getItem('marketplace-view') ?? ''
    if (view) {
      document.querySelector<HTMLLinkElement>('a[data-marketplace="' + view + '"]')?.click()
    }
  }

  protected onMarketplaceLinkClick(link: HTMLLinkElement): void {
    const targetView = link.getAttribute('data-marketplace-link') ?? ''
    localStorage.setItem('marketplace-view', targetView)
  }

  protected modifyBookmarkLinks(): void {
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

  protected modifyFeedbackForm(): void {
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

  protected async loadUserinfo(): Promise<void> {
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

  protected async loadUserinfoFromApi(): Promise<void> {
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
    const url = button.getAttribute('data-offer-edit-link') ?? ''

    this.offerLightbox = new Lightbox()
    this.offerLightbox.startLoading()
    this.offerLightbox.open()
    app
      .apiRequest(url)
      .then(data => data.html)
      .then(formHtml => {
        this.offerLightbox.displayContent(formHtml)
        this.onOfferFormLoaded()
        this.offerLightbox.stopLoading()
      })
      .catch(() => {
        app.notice.open(NoticeStyle.error, 'Could not load form, please reload and try again.')
      })
  }

  protected onDeleteOfferClick(button: HTMLButtonElement, e: Event): void {
    e.preventDefault()
    const url = button.getAttribute('data-offer-delete-link') ?? ''
    const text = button.getAttribute('data-offer-delete-confirmation') ?? ''

    if (!confirm(text)) {
      return
    }

    button.closest('.orders__item')?.classList.add('orders__item--loading')

    app
      .apiRequest(url, 'POST')
      .then(data => {
        // update userinfo
        localStorage.setItem('userinfo', JSON.stringify(data.userinfo))
        this.userinfo = data.userinfo
        // modify list
        this.modifyMarketplace()
        // message
        app.notice.open(NoticeStyle.success, 'Anzeige gelöscht', 2000)
        // close lightbox
        if (this.offerLightbox) {
          this.offerLightbox.close()
        }
      })
      .catch(() => {
        app.notice.open(NoticeStyle.error, 'Could not delete item, please reload and try again.')
      })
  }

  protected onOfferFormLoaded(): void {
    const form = this.offerLightbox.content.querySelector('form')
    if (form) {
      form.addEventListener('submit', this.onOfferFormSubmit.bind(this))
      form.querySelector('button[data-abort]')?.addEventListener('click', this.onOfferFormAbort.bind(this))
      form.querySelectorAll('.image-uploader--persisted').forEach(link => {
        link.addEventListener('click', this.onOfferImageDeleteClick.bind(this, link))
      })
      const addImageButton = form.querySelector('#image-uploader-add-button') as HTMLLinkElement
      addImageButton.addEventListener('click', this.onOfferImageNewClick.bind(this, addImageButton))

      form.querySelectorAll('.image-uploader input[type="file"]').forEach(input => {
        input.addEventListener('change', this.onOfferImageChange.bind(this, input))
      })

      const deleteButton = form.querySelector('button[data-offer-delete-link]')
      if (deleteButton) {
        deleteButton.addEventListener('click', this.onDeleteOfferClick.bind(this, deleteButton))
      }

      const agbLink = form.querySelector('a[data-agb]')
      if (agbLink) {
        agbLink.addEventListener('click', this.onAgbLinkClick.bind(this, agbLink))
      }
    }
  }

  protected onOfferImageDeleteClick(link: HTMLLinkElement, e: PointerEvent): void {
    e.preventDefault()
    // remove saved image
    if (link.classList.contains('image-uploader--persisted')) {
      link.querySelector<HTMLInputElement>('input.hidden-delete-input').value = '1'
      link.classList.remove('image-uploader--filled')
      link.classList.add('image-uploader--hidden')
    } else {
      link.querySelector('img').remove()
      link.querySelector('input').value = ''
      link.classList.remove('image-uploader--filled')
      link.classList.add('image-uploader--hidden')
    }
  }

  protected onOfferImageNewClick(link: HTMLLinkElement, e: PointerEvent): void {
    e.preventDefault()

    const firstElement = document.querySelector('.image-uploader.image-uploader--hidden input') as HTMLInputElement
    firstElement.click()
  }

  protected onOfferImageChange(input: HTMLInputElement, e: Event): void {
    const files = input.files

    if (files[0]) {
      const imageBlob = URL.createObjectURL(files[0])
      const image = new Image(100)
      image.src = imageBlob
      const uploaderElement = input.closest('.image-uploader')
      uploaderElement.querySelector('.image-uploader__drop')?.prepend(image)
      uploaderElement.classList.remove('image-uploader--hidden')
      uploaderElement.classList.add('image-uploader--filled')
      uploaderElement.addEventListener('click', this.onOfferImageDeleteClick.bind(this, uploaderElement), {once: true})
    }
  }

  protected onOfferFormAbort(e: PointerEvent): void {
    e.preventDefault()
    this.offerLightbox.close()
  }

  protected onOfferFormSubmit(e: SubmitEvent): void {
    e.preventDefault()
    const form = e.currentTarget as HTMLFormElement
    const url = form.getAttribute('action') ?? ''

    form.querySelectorAll<HTMLInputElement>('input[type="file"]').forEach(input => {
      if (!input.value) {
        input.setAttribute('disabled', 'disabled')
      }
    })

    const isRecordUpdate = form.querySelector('input[name="tx_bwguild_api[offer][__identity]"]')

    this.offerLightbox.startLoading()
    app
      .apiRequest(url, 'POST', form)
      .then(data => {
        // update userinfo
        localStorage.setItem('userinfo', JSON.stringify(data.userinfo))
        this.userinfo = data.userinfo

        if (isRecordUpdate) {
          // refresh login form
          this.offerLightbox.displayContent(data.html)
          this.onOfferFormLoaded()
          this.modifyMarketplace()
          this.offerLightbox.stopLoading()
          app.notice.open(NoticeStyle.success, 'Speichern erfolgreich', 2000)
        } else {
          // redirect to newly created offer
          window.location.href = `${this.userinfo.offers.at(-1)?.url ?? ''}#action-offer-created`
        }
      })
      .catch(() => {
        app.notice.open(NoticeStyle.error, 'Error saving data, please try again', 2000)
        this.offerLightbox.stopLoading()
      })
  }

  protected onAgbLinkClick(link: HTMLLinkElement, e: Event): void {
    e.preventDefault()
    const headline = link.getAttribute('data-agb') ?? ''
    const text = link.getAttribute('data-agb-text') ?? '[]'
    const content = `<h3>${headline}</h3>${text}`

    this.offerLightbox.isCloseable = false

    const agbLightbox = new Lightbox()
    agbLightbox.displayContent(content)
    agbLightbox.box.addEventListener('lightbox:close', () => {
      this.offerLightbox.isCloseable = true
    })
    agbLightbox.open()
  }
}

export default new Userinfo()
