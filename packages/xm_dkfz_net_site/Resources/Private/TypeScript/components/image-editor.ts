import app from './basic'
import Cropper from 'cropperjs'

type crop = Record<
  string,
  {
    cropArea: {
      x: number
      y: number
      width: number
      height: number
    }
    selectedRatio: string | number
    focusArea: Boolean | null
  }
>

class ImageEditor {
  protected crop: crop
  protected cropVariantName = 'default'
  private image: HTMLImageElement | null
  private markup: HTMLElement
  private imageCropper: Cropper
  private dummyEditor: HTMLElement

  constructor(cropArea: crop | null = null, cropVariantName: string | null = null) {
    this.cacheDom()

    if (cropArea) {
      this.crop = cropArea
    }

    if (cropVariantName) {
      this.cropVariantName = cropVariantName
    }

    if (!cropArea) {
      this.crop = {}
      this.crop[this.cropVariantName] = {
        cropArea: { x: 0, y: 0, height: 0, width: 0 },
        selectedRatio: '',
        focusArea: null
      }
    }
  }

  protected cacheDom(): Boolean {
    const dummyEditor = document.querySelector<HTMLElement>('#dummyImageEditor')

    if (!dummyEditor) {
      return false
    }

    this.dummyEditor = dummyEditor

    return true
  }

  public show(file: Blob | string): void {
    this.markup = this.dummyEditor.cloneNode(true) as HTMLElement
    this.markup.setAttribute('id', `imageEditor-${Date.now()}`)
    this.image = this.markup.querySelector<HTMLImageElement>('#imageEditorImage')

    if (this.image) {
      const src = typeof file === 'string' ? file : URL.createObjectURL(file)
      this.image.setAttribute('src', src)
      this.imageCropper = new Cropper(this.image, {
        aspectRatio: 1,
        zoomable: false,
        rotatable: false,
        scalable: false
      })

      this.bindImageReadyEvent()
      app.lightbox.appendDialogElement(this.markup)
      app.lightbox.startLoading()
      app.lightbox.showDialog()
      this.bindCropButtonClickEvent()
      this.bindCancelCropButtonClickEvent()
    }
  }

  protected bindImageReadyEvent(): void {
    this.image?.addEventListener('ready', e => {
      app.lightbox.stopLoading()

      if (this.crop[this.cropVariantName].cropArea.width === 0) {
        return
      }

      this.setAbsoluteDimensions(e.target as HTMLImageElement)
    })
  }

  protected setAbsoluteDimensions(targetImage: HTMLImageElement): void {
    const absoluteDimensions = this.calculateAbsoluteDimensions(targetImage, this.crop)

    if (absoluteDimensions) {
      this.setCropArea(absoluteDimensions)
    }
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
    })
  }

  protected cropImage(): void {
    let cropArea: crop | null = null

    if (this.image) {
      cropArea = this.calculateRelativeDimensions(this.image)
    }

    const imageCroppedEvent = new CustomEvent('imagecrop', {
      detail: {
        previewImage: this.imageCropper.getCroppedCanvas().toDataURL(),
        cropArea: cropArea
      }
    })

    document.dispatchEvent(imageCroppedEvent)
    app.lightbox.hideDialog()
  }

  protected calculateRelativeDimensions(image: HTMLImageElement): crop {
    this.crop[this.cropVariantName].cropArea.width = this.calculateRelativeUnit(image.width, this.imageCropper.getData(true).width)
    this.crop[this.cropVariantName].cropArea.height = this.calculateRelativeUnit(image.height, this.imageCropper.getData(true).height)
    this.crop[this.cropVariantName].cropArea.x = this.calculateRelativeUnit(image.width, this.imageCropper.getData(true).x)
    this.crop[this.cropVariantName].cropArea.y = this.calculateRelativeUnit(image.height, this.imageCropper.getData(true).y)

    return this.crop
  }

  protected calculateAbsoluteDimensions(image: HTMLImageElement, crop: crop): crop | null {
    this.crop[this.cropVariantName].cropArea.width = this.calculateAbsoluteUnit(image.width, crop[this.cropVariantName].cropArea.width)
    this.crop[this.cropVariantName].cropArea.height = this.calculateAbsoluteUnit(image.height, crop[this.cropVariantName].cropArea.height)
    this.crop[this.cropVariantName].cropArea.x = this.calculateAbsoluteUnit(image.width, crop[this.cropVariantName].cropArea.x)
    this.crop[this.cropVariantName].cropArea.y = this.calculateAbsoluteUnit(image.height, crop[this.cropVariantName].cropArea.y)

    return this.crop
  }

  protected calculateRelativeUnit(unit: number, dimension: number): number {
    return Math.abs(dimension / unit)
  }

  protected calculateAbsoluteUnit(unit: number, dimension: number): number {
    return Math.round(Math.abs(dimension * unit))
  }

  protected setCropArea(crop: crop): void {
    this.imageCropper.setData({
      x: crop[this.cropVariantName].cropArea.x,
      y: crop[this.cropVariantName].cropArea.y,
      width: crop[this.cropVariantName].cropArea.width,
      height: crop[this.cropVariantName].cropArea.height
    })
  }
}

export default ImageEditor
