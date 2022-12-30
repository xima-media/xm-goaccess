import app from './basic'

export enum NoticeStyle {
  info = 'info',
  warning = 'warning',
  error = 'error',
  success = 'success'
}

class Notice {
  protected element: Element

  protected message = ''

  protected paragraphElement: HTMLParagraphElement

  protected closeButton: Element

  constructor() {
    const noticeElement = document.querySelector('.notice')

    if (noticeElement) {
      this.element = noticeElement
      this.init()
      this.element.classList.remove('notice--not-loaded')
    }
  }

  protected init(): void {
    this.cacheDom()
    this.bindCloseButtonEvent()
  }

  protected cacheDom(): void {
    this.paragraphElement = this.element.querySelector('p')
    this.closeButton = this.element.querySelector('button')
  }

  protected bindCloseButtonEvent(): void {
    this.closeButton.addEventListener('click', (e: Event) => {
      this.close()
    })
  }

  public close(): void {
    this.element.classList.remove('notice--visible')
  }

  public open(style: NoticeStyle = NoticeStyle.info, message: string, duration = 0, closable = false): void {
    this.setStyle(style)
    this.setText(message)
    this.element.classList.add('notice--visible')
    if (duration) {
      setTimeout(() => this.close(), duration)
    }
    if (closable) {
      this.element.classList.add('notice--closeable')
    } else {
      this.element.classList.remove('notice--closeable')
    }
  }

  public setText(content: string): void {
    this.paragraphElement.innerText = content
  }

  public setStyle(style: NoticeStyle): void {
    const availableStyles = ['info', 'warning', 'error', 'success']
    this.element.classList.remove(
      ...availableStyles.map(name => {
        return 'notice--' + name
      })
    )
    this.element.classList.add('notice--' + style)
  }
}

export default Notice
