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
  private imageCropper: Cropper
  private dummyEditor: HTMLElement
  private readonly targetPictureElement: HTMLPictureElement
  private targetImageElementWidth: number
  private targetImageElementHeight: number
  constructor(targetPictureElement: HTMLPictureElement) {
    this.targetPictureElement = targetPictureElement

    this.cacheDom()
  }

  protected cacheDom(): Boolean {
    const dummyEditor = document.querySelector<HTMLElement>('.image-editor')
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

  public show(file: any): void {
    const markup = this.dummyEditor.cloneNode(true) as HTMLElement
    const form = this.targetPictureElement.closest('form')
    const hiddenCropInput = form?.querySelector('#hiddenCropAreaInput') as HTMLInputElement
    markup.setAttribute('id', `imageEditor-${Date.now()}`)
    const image = markup.querySelector<HTMLImageElement>('#imageEditorImage')

    if (image) {
      image?.setAttribute('src', URL.createObjectURL(file))
      app.lightbox.appendDialogElement(markup)
      app.lightbox.showDialog()

      this.imageCropper = new Cropper(image, {
        aspectRatio: 1,
        zoomable: false,
        rotatable: false,
        scalable: false
      })

      const cropButton = markup.querySelector<HTMLButtonElement>('#submitCrop')
      const cancelCropButton = markup.querySelector<HTMLButtonElement>('#cancelCrop')

      cropButton?.addEventListener('click', () => {
        const previewImage = new Image()
        previewImage.src = this.imageCropper.getCroppedCanvas().toDataURL()
        previewImage.width = this.targetImageElementWidth
        previewImage.height = this.targetImageElementHeight

        hiddenCropInput.value = this.calculateRelativeDimensions(image)
        this.replaceOriginalImage(previewImage)
        app.lightbox.hideDialog()
      })

      cancelCropButton?.addEventListener('click', () => {
        app.lightbox.hideDialog()
      })
    }
  }

  protected calculateRelativeDimensions(image: HTMLImageElement): string {
    this.dataCropArea.default.cropArea.width = this.calculateRelativeUnit(image.height, this.imageCropper.getData().height)
    this.dataCropArea.default.cropArea.height = this.calculateRelativeUnit(image.width, this.imageCropper.getData().width)
    this.dataCropArea.default.cropArea.x = this.calculateRelativeUnit(image.width, this.imageCropper.getData().x)
    this.dataCropArea.default.cropArea.y = this.calculateRelativeUnit(image.height, this.imageCropper.getData().y)

    return JSON.stringify(this.dataCropArea)
  }

  protected calculateRelativeUnit(unit: number, dimension: number): number {
    return Math.abs(dimension / unit)
  }

  protected replaceOriginalImage(previewImage: HTMLImageElement): void {
    this.targetPictureElement?.querySelector('svg')?.remove()
    this.targetPictureElement?.querySelector('img')?.remove()
    this.targetPictureElement?.prepend(previewImage)
  }
}

export default ImageEditor
