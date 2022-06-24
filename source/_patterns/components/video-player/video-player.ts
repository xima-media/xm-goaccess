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
         * @param button - DOM node
         */
        playExternalVideo (button) {
            const videoPlayerEl = button.closest('.video-player')
            const src = videoPlayerEl.dataset.src
            const fullScreen = videoPlayerEl.dataset.fullScreen === 'true' ? 'allowfullscreen="true"' : ''
            const title = videoPlayerEl.dataset.title

            // append video
            videoPlayerEl.insertAdjacentHTML('beforeend', '<iframe class="video-player__iframe" src="' + src + '" title="' + title + '" ' + fullScreen + '></iframe>')
        }
}

/**
 *     @section 3. Export class
 */

export default (new VideoPlayer())

// end of video-player.js
