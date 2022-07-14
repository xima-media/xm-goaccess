import './lightbox.scss'

class Lightbox {

  protected box: Element;

  public content: Element;

  public isCloseable = true;

  protected closeButton: Element;

  constructor() {
    const boxElement = document.querySelector('.lightbox');

    if (boxElement) {
      this.box = boxElement
      this.init()
    }
  }

  protected init() {
    this.cacheDom()
    this.bindEvents()
  }

  protected cacheDom() {
    this.content = this.box.querySelector('.lightbox__wrap')
    this.closeButton = this.box.querySelector('.lightbox__close')
  }

  protected bindEvents() {
    this.closeButton.addEventListener('click', (e: Event) => {
      this.close()
    })
  }

  protected bindEscCloseEvent() {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.onEscKeyPress()
      }
    })
  }

  protected onEscKeyPress() {
    this.close();
  }

  public close() {
    document.querySelector('body').classList.remove('open-lightbox')
    document.removeEventListener('keydown', this.onEscKeyPress.bind(this));
    this.stopLoading()
    this.clear()
  }

  public open() {
    document.querySelector('body').classList.add('open-lightbox')
    if (this.isCloseable) {
      this.bindEscCloseEvent()
    }
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
