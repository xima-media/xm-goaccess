class Organigramm {
  protected boxes: NodeListOf<HTMLDivElement>

  protected overlayContainer: HTMLDivElement

  protected closeButtons: NodeListOf<HTMLAnchorElement>

  protected backgroundClickEventHandler = this.onBackgroundClick.bind(this)

  constructor() {
    this.cacheDom()

    if (!this.boxes || !this.overlayContainer) {
      return
    }

    this.bindEvents()
  }

  protected cacheDom(): boolean {
    const boxes = document.querySelectorAll<HTMLDivElement>('.organigram__boxes')
    const overlayContainer = document.querySelector<HTMLDivElement>('.organigram__overlays')
    const closeButtons = document.querySelectorAll<HTMLAnchorElement>('.organigram__detail .btn-close')

    if (!boxes || !overlayContainer || !closeButtons) {
      return false
    }

    this.boxes = boxes
    this.overlayContainer = overlayContainer
    this.closeButtons = closeButtons

    return true
  }

  protected close() {
    document.querySelectorAll('.organigram__detail').forEach(detail => {
      detail.classList.remove('show')
    })
    this.overlayContainer.classList.remove('active')
    window.removeEventListener('click', this.backgroundClickEventHandler)
  }

  protected bindEscKeyDown() {
    document.addEventListener('keydown', this.onEscDown.bind(this))
  }

  protected onEscDown(e: KeyboardEvent) {
    if (e.key === 'Escape') {
      this.close()
    }
  }

  protected bindBackgroundClickEvent() {
    setTimeout(() => window.addEventListener('click', this.backgroundClickEventHandler), 1)
  }

  protected bindEvents() {
    this.closeButtons.forEach(btn => {
      btn.addEventListener('click', this.close.bind(this))
    })

    this.boxes.forEach(box => {
      box.addEventListener('click', this.onBoxClick.bind(this))
    })
  }

  protected onBoxClick(e: Event) {
    this.close()
    this.bindEscKeyDown()
    const box = e.currentTarget as HTMLElement
    const boxId = box.getAttribute('data-box-id') ?? ''
    this.open(boxId)
  }

  protected open(boxId: string) {
    document.querySelector('.organigram__detail[data-box-target-id="' + boxId + '"]')?.classList.add('show')
    this.overlayContainer.classList.add('active')
    this.bindBackgroundClickEvent()
  }

  protected onBackgroundClick(e: PointerEvent) {
    // @ts-ignore
    const isClickInsideContent = e.composedPath().includes(this.overlayContainer.querySelector('.organigram__detail.show'))
    if (!isClickInsideContent) {
      this.close()
    }
  }
}

export default new Organigramm()
