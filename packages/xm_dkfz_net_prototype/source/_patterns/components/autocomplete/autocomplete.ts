/**
 *    Autocomplete
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
import './autocomplete.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'
// @ts-ignore
import Awesomplete from 'awesomplete'

/**
 *     @section 2. Class
 */

class Autocomplete {
    autocompleteEl

    constructor () {
        app.log('component "autocomplete" loaded')

        this.autocompleteEl = document.querySelectorAll<HTMLInputElement>('.autocomplete')

        // methods
        if (this.autocompleteEl) {
            this.init()
        }
    }

    /**
     * Init
     */
    init () {
        // variables
        const self = this

        // get every select
        self.autocompleteEl.forEach((autocompleteEl) => {
            const autocomplete = new Awesomplete(autocompleteEl)
            const url = autocompleteEl.dataset.autocompleteUrl

            // event: keyup
            autocompleteEl.addEventListener('keyup', (key) => self.change(autocomplete, autocompleteEl, url, key))
        })
    }

    /**
     * Input change
     */
    change (autocomplete: any, autocompleteEl: HTMLInputElement, url: string, key: KeyboardEvent) {
        const whichKey = key.which
        const completeUrl = app.prototype ? url : url + '&L=' + app.lang + '&tx_solr[queryString]=' + autocompleteEl.value.toLowerCase() + '&tx_solr[callback]'

        // 40 && 38 == up/down key
        if (whichKey === 40 || whichKey === 38) {
            app.log('hoch/runter runter');
        } else {
            // fetch data
            fetch(completeUrl)
                .then(response => {
                    return response.json()
                })
                .then(suggestions => {
                    // create suggestion list
                    let list = []
                    for (let listKey in suggestions) {
                        list.push(listKey)
                    }

                    // append list
                    autocomplete.list = list
                    autocomplete.evaluate()
                })
        }
    }
}

/**
 *     @section 3. Export class
 */

export default (new Autocomplete())

// end of autocomplete.js
