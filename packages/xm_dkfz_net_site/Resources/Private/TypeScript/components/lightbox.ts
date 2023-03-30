export enum LightboxStyle {
  default,
  sidebar,
  warning
}

class Lightbox {
  public box: Element

  public content: Element

  public dialog: Element

  public isCloseable = true
  public preserveContent = false

  protected closeButton: Element

  protected backgroundClickEventHandler = this.onBackgroundClick.bind(this)

  protected escKeyPressEventHandler = this.onEscKeyPress.bind(this)

  protected root: HTMLHtmlElement

  constructor() {
    this.createLightboxInstance()
    this.cacheDom()
    this.bindCloseButtonEvent()
  }

  protected createLightboxInstance(): void {
    const dummyLightbox = document.getElementById('dummyLightbox') as HTMLElement
    this.box = dummyLightbox.cloneNode(true) as HTMLElement
    this.box.id = `lightbox-${Date.now()}`
    document.body.append(this.box)
  }

  protected cacheDom() {
    const content = this.box.querySelector('.lightbox__wrap')
    const closeButton = this.box.querySelector('.lightbox__close')
    const root = document.querySelector('html')

    if (!content || !closeButton || !root) {
      return false
    }

    this.content = content
    this.closeButton = closeButton
    this.root = root

    return true
  }

  protected bindCloseButtonEvent() {
    this.closeButton?.addEventListener('click', (e: Event) => {
      e.preventDefault()
      this.close()
    })
  }

  protected bindEscKeyPressEvent() {
    document.addEventListener('keydown', this.escKeyPressEventHandler)
  }

  protected bindBackgroundClickEvent() {
    setTimeout(() => {
      window.addEventListener('click', this.backgroundClickEventHandler)
    }, 1)
  }

  protected onBackgroundClick(e: PointerEvent) {
    // @ts-expect-error
    const isClickInsideContent = e.composedPath().includes(this.box.querySelector('.lightbox__content'))
    // @ts-expect-error
    const isClickInAutocomplete = e.composedPath().includes(document.querySelector('.autocomplete'))
    if (this.isCloseable && !(isClickInsideContent || isClickInAutocomplete)) {
      this.close()
    }
  }

  protected onEscKeyPress(e: KeyboardEvent) {
    if (this.isCloseable && e.key === 'Escape') {
      this.close()
    }
  }

  protected removeAllListener() {
    window.removeEventListener('click', this.backgroundClickEventHandler)
    document.removeEventListener('keydown', this.escKeyPressEventHandler)
  }

  public close() {
    const lightboxCloseEvent = new Event('lightbox:close', { bubbles: true })
    this.box.classList.add('lightbox--closing')
    this.removeAllListener()

    setTimeout(() => {
      this.box.dispatchEvent(lightboxCloseEvent)
      this.root.classList.remove('open-lightbox')
      this.box.classList.remove('lightbox--closing')
      this.box.classList.remove('lightbox--open')
      this.stopLoading()

      if (!this.preserveContent) {
        this.destroy()
      }
    }, 400)
  }

  public destroy() {
    this.box.remove()
  }

  public open(style: LightboxStyle = 0) {
    const lightboxOpenEvent = new Event('lightbox:open', { bubbles: true })
    this.setStyle(style)
    this.root.classList.add('open-lightbox')
    this.box.classList.add('lightbox--open')
    this.box.dispatchEvent(lightboxOpenEvent)
    this.bindEscKeyPressEvent()
    this.bindBackgroundClickEvent()
  }

  public startLoading() {
    this.box.classList.add('lightbox--loading')
    this.box.classList.add('lightbox--loading-visual')
  }

  public stopLoading() {
    this.box.classList.remove('lightbox--loading')

    setTimeout(() => {
      this.box.classList.remove('lightbox--loading-visual')
    }, 500)
  }

  public clear() {
    this.content.innerHTML = ''
  }

  public displayContent(content: string) {
    this.content.innerHTML = content
  }

  public appendElement(element: HTMLElement) {
    this.content.append(element)
  }

  public setStyle(style: LightboxStyle): void {
    const availableStyles = ['default', 'sidebar', 'warning']
    this.box.classList.remove(
      ...availableStyles.map(name => {
        return 'lightbox--' + name
      })
    )
    this.box.classList.add('lightbox--' + availableStyles[style])
    this.root.classList.remove(
      ...availableStyles.map(name => {
        return 'lightbox-style-' + name
      })
    )
    this.root.classList.add('lightbox-style-' + availableStyles[style])
  }
}

export default Lightbox
