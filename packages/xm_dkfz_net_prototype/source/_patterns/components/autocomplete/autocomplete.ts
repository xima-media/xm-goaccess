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
import Awesomplete from 'awesomplete'

/**
 *     @section 2. Class
 */

class Autocomplete {
    autocompleteEl

    constructor () {
        app.log('component "autocomplete" loaded')

        this.autocompleteEl = document.querySelectorAll<HTMLElement>('.autocomplete')

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
            console.log('autocompleteEl')
            console.log(autocompleteEl)

            const autocomplete = new Awesomplete(autocompleteEl)
            const url = autocompleteEl.dataset.autocompleteUrl
            // const lang = app.lang


            autocompleteEl.addEventListener('keyup', () => self.change(autocompleteEl, url))
        })
        //         let $input = $(e).not('[aria-autocomplete]'),
        //             autocomplete = new Awesomplete($input[0]),
        //             $form = $input.closest('form'),
        //             url = $form.data('suggest'),
        //             lang = $form.find('input[name="L"]').val();
    }

    /**
     * Input change
     */
    change (autocompleteEl, url) {
        console.log(autocompleteEl)
        console.log(url)
    }
}

/**
 *     @section 3. Export class
 */

export default (new Autocomplete())

// end of autocomplete.js
