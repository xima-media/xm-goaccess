/**
 *    App
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
import './basic.scss'

/**
 *     @section 2. Class
 */

export default {
    /**
     * Variables
     */
    debug: true,
    lang: document.documentElement.lang,
    transitionTime: 300, // @todo aus CSS auslesen

    /**
     * get hex from emoji
     * @param emoji string
     */
    emoji (emoji) {
        const hex = emoji.codePointAt(0).toString(16)
        const emo = String.fromCodePoint('0x' + hex)
        return emo
    },

    /**
     * Print log information if debug is true
     * @param message string
     * @param emoji string
     * @param hexColor string
     */
    log (message, emoji, hexColor) {
        if (this.debug) {
            const icon = emoji ? this.emoji(emoji) : this.emoji('🔥')
            const color = hexColor ? hexColor : '#bada55' // eslint-disable-line no-unneeded-ternary
            // @todo ausgabe von arrays und objekten ermöglichen
            console.log('%c' + icon + ' ' + message, 'color: ' + color)
        }
    },
}

// end of basic.js
