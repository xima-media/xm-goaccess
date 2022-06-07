/**
 *    Debug Screenshot
 *
 *    @tableofcontent
 *      1. Dependencies
 *      2. Class
 *      3. Export class
 *
 */

/**
 *     @section 1. Dependencies
 */

// import css dependencies
import './debug-screenshot.scss'

// import js dependencies
import app from '../basic/basic'

/**
 *     @section 2. Class
 */

class DebugScreenshot {
    screenshotVisible:boolean
    debugScreenEl
    changeScreenshotVisibilityInputEl
    changeScreenshotOpacityInputEl
    changeScreenshotPositionInputEl

    constructor () {
        // variables
        this.screenshotVisible = false
        this.debugScreenEl = document.querySelector<HTMLElement>('.debug-screenshot')

        if (this.debugScreenEl) {
            this.changeScreenshotVisibilityInputEl = document.querySelector<HTMLInputElement>('.fx--change-screenshot-visibility')
            this.changeScreenshotOpacityInputEl = document.querySelector<HTMLInputElement>('.fx--change-screenshot-opacity')
            this.changeScreenshotPositionInputEl = document.querySelector<HTMLInputElement>('.fx--change-screenshot-position')

            // methods
            this.init()
            this.events()
        }
    }

    /**
     * Init
     */
    init () {
        // variables
        const self = this

        // screenshot: visibility
        if (localStorage.getItem('screenshot-visibility')) {
            self.changeScreenshotVisibilityInputEl.checked = true
            self.screenshotVisibility(true)
        }

        // screenshot: opacity
        if (localStorage.getItem('screenshot-opacity')) {
            self.changeScreenshotOpacityInputEl.value = String(parseInt(localStorage.getItem('screenshot-opacity')))
            self.screenshotOpacity(parseInt(localStorage.getItem('screenshot-opacity')))
        }

        // screenshot: position
        if (localStorage.getItem('screenshot-position')) {
            self.changeScreenshotPositionInputEl.value = String(parseInt(localStorage.getItem('screenshot-position')))
            self.screenshotPosition(parseInt(localStorage.getItem('screenshot-position')))
        }
    }

    /**
     * Events
     */
    events () {
        // variables
        const self = this

        // screenshot: visibility
        self.changeScreenshotVisibilityInputEl.addEventListener('input', () => {
            self.screenshotVisibility(!self.screenshotVisible)
        })

        // screenshot: opacity
        self.changeScreenshotOpacityInputEl.addEventListener('input', () => {
            self.screenshotOpacity(parseInt(self.changeScreenshotOpacityInputEl.value))
        })

        // screenshot: position
        self.changeScreenshotPositionInputEl.addEventListener('input', () => {
            self.screenshotPosition(parseInt(self.changeScreenshotPositionInputEl.value))
        })
    }

    /**
     * Activate screenshot as background
     */
    screenshotVisibility (visible:boolean) {
        // variables
        const self = this
        const backgroundImage = `url(//${window.location.host}/dist/image/debug/screenshots/${document.documentElement.dataset.debugScreenshot}.png)`

        // check if width and height is defined
        if (visible) {
            // set background image
            self.debugScreenEl.style.backgroundImage = backgroundImage

            // save behavior in local storage
            localStorage.setItem('screenshot-visibility', String(visible))

            // set visibility
            self.screenshotVisible = true
        } else {
            // remove background image
            self.debugScreenEl.style.removeProperty('background-image')

            // remove local storage
            localStorage.removeItem('screenshot-visibility')

            // set visibility
            self.screenshotVisible = false
        }
    }

    /**
     * Change screenshot opacity
     * @param value number
     */
    screenshotOpacity(value: number) {
        // set css variable
        document.documentElement.style.setProperty('--debug-screenshot-opacity', String(value / 100))

        // save behavior in local storage
        localStorage.setItem('screenshot-opacity', String(value))
    }

    /**
     * Change screenshot position
     * @param value number
     */
    screenshotPosition (value: number) {
        // set css variable
        document.documentElement.style.setProperty('--debug-screenshot-position', value + '%')

        // save behavior in local storage
        localStorage.setItem('screenshot-position', String(value))
    }
}

/**
 *     @section 3. Export class
 */

export default (new DebugScreenshot())

// end of debug-screenshot.js
