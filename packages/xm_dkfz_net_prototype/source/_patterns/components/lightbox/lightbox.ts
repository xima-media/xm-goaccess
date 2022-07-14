import './lightbox.scss'

class Lightbox {

  protected box: Element;

  public content: Element;

  public isCloseable = true;

  protected closeButton: Element;

  protected backgroundClickEventHandler = this.onBackgroundClick.bind(this)

  protected escKeyPressEventHandler = this.onEscKeyPress.bind(this)

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
    if (this.isCloseable && !isClickInsideContent) {
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
    document.querySelector('body').classList.remove('open-lightbox')
    this.removeAllListener()
    this.stopLoading()
    this.clear()
  }

  public open() {
    document.querySelector('body').classList.add('open-lightbox')
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

}

export default Lightbox
