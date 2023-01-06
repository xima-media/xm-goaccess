class VideoPlayer {
  playButtonEl: NodeListOf<HTMLButtonElement>

  constructor() {
    // variables
    this.playButtonEl = document.querySelectorAll('.--play-video')

    // methods
    this.bindEvents()
  }

  bindEvents(): void {
    // click: play button
    this.playButtonEl.forEach(button => {
      button.addEventListener('click', () => {
        this.playExternalVideo(button)
      })
    })

    // media slider: remove
    document.addEventListener('video:allowed', () => {
      this.playAllExternalVideos()
    })
  }

  playAllExternalVideos(): void {
    this.playButtonEl.forEach(button => {
      this.playExternalVideo(button)
    })
  }

  playExternalVideo(button: HTMLButtonElement): void {
    const videoPlayerEl: HTMLDivElement | null = button.closest('.video-player')
    if (!videoPlayerEl) {
      return
    }
    const videoId = videoPlayerEl.dataset.videoId ?? ''
    const videoMimeType = videoPlayerEl.dataset.videoMimeType ?? ''
    const fullScreen = videoPlayerEl.dataset.fullScreen === 'true' ? 'allowfullscreen="true"' : ''
    const title = videoPlayerEl.dataset.title ?? ''
    const videoSrc = this.buildVideoUrlByMimetype(videoMimeType, videoId)

    // append video
    if (videoSrc !== '') {
      const iframeElement = `<iframe class="video-player__iframe" src="${videoSrc}" title="${title}" ${fullScreen}></iframe>`
      videoPlayerEl.insertAdjacentHTML('beforeend', iframeElement)
    } else if (videoMimeType === 'video/mp4') {
      videoPlayerEl.classList.add('video-player--hide-elements')
    }
  }

  buildVideoUrlByMimetype(mimeType: string, videoId: string): string {
    if (mimeType === 'video/youtube') {
      return `https://www.youtube.com/embed/${videoId}`
    }

    if (mimeType === 'video/vimeo') {
      return `https://player.vimeo.com/video/${videoId}`
    }

    return ''
  }
}

export default new VideoPlayer()
