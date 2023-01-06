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
    if (!this.cacheDom()) {
      return
    }

    this.init()
    this.element.classList.remove('notice--not-loaded')
  }

  protected init(): void {
    this.cacheDom()
    this.bindCloseButtonEvent()
  }

  protected cacheDom(): boolean {
    const noticeElement = document.querySelector('.notice')
    const paragraphElement = document.querySelector<HTMLParagraphElement>('.notice p')
    const closeButton = document.querySelector('.notice button')

    if (!noticeElement || !paragraphElement || !closeButton) {
      return false
    }

    this.element = noticeElement
    this.paragraphElement = paragraphElement
    this.closeButton = closeButton

    return true
  }

  protected bindCloseButtonEvent(): void {
    this.closeButton.addEventListener('click', (e: Event) => {
      e.preventDefault()
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
      setTimeout(() => {
        this.close()
      }, duration)
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
