class VideoPlayer {
  playButtonEl: NodeListOf<HTMLButtonElement>

  constructor() {
    // variables
    this.playButtonEl = document.querySelectorAll('.--play-video')

    // methods
    this.events()
  }

  events() {
    const self = this

    // click: play button
    self.playButtonEl.forEach((button) => button.addEventListener('click', () => self.playExternalVideo(button)))

    // media slider: remove
    document.addEventListener('video:allowed', () => {
      self.playAllExternalVideos()
    })
  }

  playAllExternalVideos() {
    const self = this

    self.playButtonEl.forEach((button) => self.playExternalVideo(button))
  }

  playExternalVideo(button: HTMLButtonElement) {
    const videoPlayerEl: HTMLDivElement = button.closest('.video-player')
    const videoId: String = videoPlayerEl.dataset.videoId
    const videoMimeType: String = videoPlayerEl.dataset.videoMimeType
    const fullScreen = videoPlayerEl.dataset.fullScreen === 'true' ? 'allowfullscreen="true"' : ''
    const title: String = videoPlayerEl.dataset.title
    const videoSrc: String = this.buildVideoUrlByMimetype(videoMimeType, videoId)

    // append video
    if (videoSrc) {
      videoPlayerEl.insertAdjacentHTML('beforeend', '<iframe class="video-player__iframe" src="' + videoSrc + '" title="' + title + '" ' + fullScreen + '></iframe>')
    } else if (videoMimeType === 'video/mp4') {
      videoPlayerEl.classList.add('video-player--hide-elements')
    }
  }

  buildVideoUrlByMimetype(mimeType: String, videoId: String): String {

    let videoEmbedUrl

    switch (mimeType) {
      case 'video/youtube':
        videoEmbedUrl = `https://www.youtube.com/embed/${videoId}`;
        break;
      case 'video/vimeo':
        videoEmbedUrl = `https://player.vimeo.com/video/${videoId}`;
        break;
      default:
        break;
    }

    return videoEmbedUrl
  }
}

export default (new VideoPlayer())
