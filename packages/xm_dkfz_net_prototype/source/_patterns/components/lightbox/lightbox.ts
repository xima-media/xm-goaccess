import './lightbox.scss'

export enum LightboxStyle {
  default,
  sidebar,
  warning
}

class Lightbox {

  protected box: Element;

  public content: Element;

  public isCloseable = true;

  protected closeButton: Element;

  protected backgroundClickEventHandler = this.onBackgroundClick.bind(this)

  protected escKeyPressEventHandler = this.onEscKeyPress.bind(this)

  protected root = document.querySelector('html');

  constructor() {
    const boxElement = document.querySelector('.lightbox');

    if (boxElement) {
      this.box = boxElement
      this.init()
    }
  }

  protected init() {
    this.cacheDom()
    this.bindCloseButtonEvent()
  }

  protected cacheDom() {
    this.content = this.box.querySelector('.lightbox__wrap')
    this.closeButton = this.box.querySelector('.lightbox__close')
  }

  protected bindCloseButtonEvent() {
    this.closeButton.addEventListener('click', (e: Event) => {
      this.close()
    })
  }

  protected bindEscKeyPressEvent() {
    document.addEventListener('keydown', this.escKeyPressEventHandler)
  }

  protected bindBackgroundClickEvent() {
    setTimeout(() => window.addEventListener('click', this.backgroundClickEventHandler), 1)
  }

  protected onBackgroundClick(e: PointerEvent) {
    const isClickInsideContent = e.composedPath().includes(this.box.querySelector('.lightbox__content'))
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

    this.box.classList.add('lightbox--closing')
    this.removeAllListener()

    if(this.root.dataset.lightBoxType !== '') {
      const lightBoxCloseEvent = new Event('lightboxClose')
      document.dispatchEvent(lightBoxCloseEvent);
    }

    setTimeout(() => {
      this.root.classList.remove('open-lightbox')
      this.box.classList.remove('lightbox--closing')
      this.root.dataset.lightBoxType = ''
      this.stopLoading()
      this.clear()
    }, 400);
  }

  public open(style: LightboxStyle = 0, type: string = '') {
    this.setStyle(style)
    this.root.classList.add('open-lightbox')
    this.root.dataset.lightBoxType = type
    this.bindEscKeyPressEvent()
    this.bindBackgroundClickEvent()
  }

  public startLoading() {
    this.box.classList.add('lightbox--loading')
  }

  public stopLoading() {
    this.box.classList.remove('lightbox--loading')
  }

  public clear() {
    this.content.innerHTML = '';
  }

  public displayContent(content: string) {
    this.content.innerHTML = content
  }

  public appendElement(element: HTMLElement) {
    this.content.append(element)
  }

  public setStyle(style: LightboxStyle): void {
    const availableStyles = ['default', 'sidebar', 'warning']
    this.box.classList.remove(...availableStyles.map(name => {
      return 'lightbox--' + name
    }))
    this.box.classList.add('lightbox--' + availableStyles[style])
    this.root.classList.remove(...availableStyles.map(name => {
      return 'lightbox-style-' + name
    }))
    this.root.classList.add('lightbox-style-' + availableStyles[style])
  }

}

export default Lightbox
