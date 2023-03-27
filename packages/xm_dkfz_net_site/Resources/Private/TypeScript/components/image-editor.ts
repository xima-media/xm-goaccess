import app from './basic'
import Cropper from 'cropperjs'

interface cropArea {
  default: {
    cropArea: {
      x: number
      y: number
      width: number
      height: number
    }
    selectedRatio: string | number
    focusArea: Boolean
  }
}

class ImageEditor {
  private readonly dataCropArea: cropArea = {
    default: {
      cropArea: {
        x: 0,
        y: 0,
        width: 0,
        height: 0
      },
      selectedRatio: '4:3',
      focusArea: false
    }
  }
  private image: HTMLImageElement | null
  private markup: HTMLElement
  private imageCropper: Cropper
  private dummyEditor: HTMLElement
  private readonly targetPictureElement: HTMLPictureElement
  private targetImageElementWidth: number
  private targetImageElementHeight: number
  constructor(targetPictureElement: HTMLPictureElement, cropArea: cropArea | null = null) {
    this.targetPictureElement = targetPictureElement

    this.cacheDom()

    if (cropArea) {
      this.dataCropArea = cropArea
    }
  }

  protected cacheDom(): Boolean {
    const dummyEditor = document.querySelector<HTMLElement>('#dummyImageEditor')
    const targetImageElement = this.targetPictureElement.querySelector<HTMLImageElement>('img')
      ? this.targetPictureElement.querySelector<HTMLImageElement>('img')
      : this.targetPictureElement.querySelector<HTMLImageElement>('svg')

    if (!dummyEditor || !targetImageElement) {
      return false
    }

    this.dummyEditor = dummyEditor
    this.targetImageElementWidth = targetImageElement.clientWidth
    this.targetImageElementHeight = targetImageElement.clientHeight

    return true
  }

  public show(file: Blob): void {
    this.markup = this.dummyEditor.cloneNode(true) as HTMLElement
    this.markup.setAttribute('id', `imageEditor-${Date.now()}`)
    this.image = this.markup.querySelector<HTMLImageElement>('#imageEditorImage')

    if (this.image) {
      this.image.setAttribute('src', URL.createObjectURL(file))
      this.imageCropper = new Cropper(this.image, {
        aspectRatio: 1,
        zoomable: false,
        rotatable: false,
        scalable: false
      })

      this.bindImageReadyEvent()
      app.lightbox.appendDialogElement(this.markup)
      app.lightbox.showDialog()
      this.bindCropButtonClickEvent()
      this.bindCancelCropButtonClickEvent()
    }
  }

  protected bindImageReadyEvent(): void {
    if (this.dataCropArea.default.cropArea.width === 0 && !this.image) {
      return
    }

    this.image?.addEventListener('ready', e => {
      const targetImage = e.target as HTMLImageElement
      const absoluteDimensions = this.calculateAbsoluteDimensions(targetImage, this.dataCropArea)

      if (absoluteDimensions) {
        this.setCropArea(absoluteDimensions)
      }
    })
  }

  protected bindCancelCropButtonClickEvent(): void {
    const cancelCropButton = this.markup.querySelector<HTMLButtonElement>('#cancelCrop')
    cancelCropButton?.addEventListener('click', () => {
      app.lightbox.hideDialog()
    })
  }

  protected bindCropButtonClickEvent(): void {
    const cropButton = this.markup.querySelector<HTMLButtonElement>('#submitCrop')
    cropButton?.addEventListener('click', () => {
      this.cropImage()
      this.showImageEditButton()
    })
  }

  protected cropImage(): void {
    const form = this.targetPictureElement.closest('form')
    const hiddenCropInput = form?.querySelector('#hiddenCropAreaInput') as HTMLInputElement
    const previewImage = new Image()
    previewImage.src = this.imageCropper.getCroppedCanvas().toDataURL()
    previewImage.width = this.targetImageElementWidth
    previewImage.height = this.targetImageElementHeight

    if (this.image) {
      hiddenCropInput.value = this.calculateRelativeDimensions(this.image)
    }
    this.replaceOriginalImage(previewImage)
    app.lightbox.hideDialog()
  }

  protected calculateRelativeDimensions(image: HTMLImageElement): string {
    this.dataCropArea.default.cropArea.width = this.calculateRelativeUnit(image.width, this.imageCropper.getData(true).width)
    this.dataCropArea.default.cropArea.height = this.calculateRelativeUnit(image.height, this.imageCropper.getData(true).height)
    this.dataCropArea.default.cropArea.x = this.calculateRelativeUnit(image.width, this.imageCropper.getData(true).x)
    this.dataCropArea.default.cropArea.y = this.calculateRelativeUnit(image.height, this.imageCropper.getData(true).y)

    return JSON.stringify(this.dataCropArea)
  }

  protected calculateAbsoluteDimensions(image: HTMLImageElement, cropArea: cropArea): cropArea | null {
    this.dataCropArea.default.cropArea.width = this.calculateAbsoluteUnit(image.width, cropArea.default.cropArea.width)
    this.dataCropArea.default.cropArea.height = this.calculateAbsoluteUnit(image.height, cropArea.default.cropArea.height)
    this.dataCropArea.default.cropArea.x = this.calculateAbsoluteUnit(image.width, cropArea.default.cropArea.x)
    this.dataCropArea.default.cropArea.y = this.calculateAbsoluteUnit(image.height, cropArea.default.cropArea.y)

    return this.dataCropArea
  }

  protected calculateRelativeUnit(unit: number, dimension: number): number {
    return Math.abs(dimension / unit)
  }

  protected calculateAbsoluteUnit(unit: number, dimension: number): number {
    return Math.round(Math.abs(dimension * unit))
  }

  protected replaceOriginalImage(previewImage: HTMLImageElement): void {
    this.targetPictureElement?.querySelector('svg')?.remove()
    this.targetPictureElement?.querySelector('img')?.remove()
    this.targetPictureElement?.prepend(previewImage)
  }

  protected showImageEditButton(): void {
    const imageEditButton = this.targetPictureElement.parentNode?.querySelector<HTMLButtonElement>('.userimage__edit-button')
    imageEditButton?.classList.remove('userimage__edit-button--hidden')
  }

  protected setCropArea(cropArea: cropArea): void {
    this.imageCropper.setData({
      x: cropArea.default.cropArea.x,
      y: cropArea.default.cropArea.y,
      width: cropArea.default.cropArea.width,
      height: cropArea.default.cropArea.height
    })
  }
}

export default ImageEditor
