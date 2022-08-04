/**
 *    Video player
 *
 *    @tableofcontent
 *      1. Dependencies
 *       1.1 Import css
 *       1.2 Import js
 *      2. Class
 *      3. Export class
 *
 */

/**
 *     @section 1. Dependencies
 */

/** @section 1.1 Import css */
import './video-player.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class VideoPlayer {
    playButtonEl: NodeListOf<HTMLButtonElement>

    constructor () {
        app.log('component "Video player" loaded')

        // variables
        this.playButtonEl = document.querySelectorAll('.--play-video')

        // methods
        this.events()

    }

    /**
         * Events
         */
        events () {
            const self = this

            // click: play button
            self.playButtonEl.forEach((button) => button.addEventListener('click', () => self.playExternalVideo(button)))

            // media slider: remove
            document.addEventListener('video:allowed', () => {
                self.playAllExternalVideos()
            })
        }

        playAllExternalVideos () {
            const self = this

            self.playButtonEl.forEach((button) => self.playExternalVideo(button))
        }

       
        /**
         * Play a single external video
         * @param button 
         */
        playExternalVideo (button: HTMLButtonElement) {
            const videoPlayerEl: HTMLDivElement = button.closest('.video-player')
            const videoId: String = videoPlayerEl.dataset.videoId
            const videoMimeType: String = videoPlayerEl.dataset.videoMimeType
            const fullScreen = videoPlayerEl.dataset.fullScreen === 'true' ? 'allowfullscreen="true"' : ''
            const title: String = videoPlayerEl.dataset.title
            const videoSrc: String = this.buildVideoUrlByMimetype(videoMimeType, videoId)

            // append video
            if(videoSrc) {
                videoPlayerEl.insertAdjacentHTML('beforeend', '<iframe class="video-player__iframe" src="' + videoSrc + '" title="' + title + '" ' + fullScreen + '></iframe>')
            }
        }

        /**
         * 
         * @param mimeType 
         * @param videoId 
         * @returns 
         */
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

/**
 *     @section 3. Export class
 */

export default (new VideoPlayer())

// end of video-player.js
