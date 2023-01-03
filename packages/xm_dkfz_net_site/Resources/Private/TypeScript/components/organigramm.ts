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

  protected close(): void {
    document.querySelectorAll('.organigram__detail').forEach(detail => {
      detail.classList.remove('show')
    })
    this.overlayContainer.classList.remove('active')
    window.removeEventListener('click', this.backgroundClickEventHandler)
  }

  protected bindEscKeyDown(): void {
    document.addEventListener('keydown', this.onEscDown.bind(this))
  }

  protected onEscDown(e: KeyboardEvent): void {
    if (e.key === 'Escape') {
      this.close()
    }
  }

  protected bindBackgroundClickEvent(): void {
    setTimeout(() => {
      window.addEventListener('click', this.backgroundClickEventHandler)
    }, 1)
  }

  protected bindEvents(): void {
    this.closeButtons.forEach(btn => {
      btn.addEventListener('click', this.close.bind(this))
    })

    this.boxes.forEach(box => {
      box.addEventListener('click', this.onBoxClick.bind(this))
    })
  }

  protected onBoxClick(e: Event): void {
    this.close()
    this.bindEscKeyDown()
    const box = e.currentTarget as HTMLElement
    const boxId = box.getAttribute('data-box-id') ?? ''
    this.open(boxId)
  }

  protected open(boxId: string): void {
    document.querySelector('.organigram__detail[data-box-target-id="' + boxId + '"]')?.classList.add('show')
    this.overlayContainer.classList.add('active')
    this.bindBackgroundClickEvent()
  }

  protected onBackgroundClick(e: PointerEvent): void {
    // @ts-expect-error
    const isClickInsideContent = e.composedPath().includes(this.overlayContainer.querySelector('.organigram__detail.show'))
    if (!isClickInsideContent) {
      this.close()
    }
  }
}

export default new Organigramm()
