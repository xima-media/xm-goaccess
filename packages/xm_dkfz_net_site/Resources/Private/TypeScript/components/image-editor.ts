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
      selectedRatio: 1,
      focusArea: false
    }
  }
  private imageCropper: Cropper
  constructor() {}

  public show(markup: HTMLElement, file: any): void {
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
        const croppedimage = this.imageCropper.getCroppedCanvas().toDataURL()

        this.calculateRelativeDimensions(image)

        const previewImage = new Image()
        previewImage.src = croppedimage
        previewImage.width = 150
        previewImage.height = 150
        const profileImage = app.lightbox.content.querySelector<HTMLImageElement>('.userimage picture')

        profileImage?.querySelector('svg')?.remove()
        profileImage?.prepend(previewImage)

        app.lightbox.hideDialog()
      })

      cancelCropButton?.addEventListener('click', () => {
        app.lightbox.hideDialog()
      })
    }
  }

  protected calculateRelativeDimensions(image: HTMLImageElement): void {
    this.dataCropArea.default.cropArea.width = this.calculateRelativeUnit(image.height, this.imageCropper.getData().height)
    this.dataCropArea.default.cropArea.height = this.calculateRelativeUnit(image.width, this.imageCropper.getData().width)
    this.dataCropArea.default.cropArea.x = this.calculateRelativeUnit(image.width, this.imageCropper.getData().x)
    this.dataCropArea.default.cropArea.y = this.calculateRelativeUnit(image.height, this.imageCropper.getData().y)

    console.log(this.dataCropArea)
  }

  protected calculateRelativeUnit(unit: number, dimension: number): number {
    return Math.abs(dimension / unit)
  }
}

export default ImageEditor
