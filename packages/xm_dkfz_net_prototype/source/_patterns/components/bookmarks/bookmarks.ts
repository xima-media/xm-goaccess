/**
 *    Bookmarks
 *
 *    @tableOfContent
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
import './bookmarks.scss'

/** @section 1.2 Import js */
import app from '../basic/basic'

/**
 *     @section 2. Class
 */


class Bookmarks {
    constructor () {
        app.log('component "bookmarks" loaded')
        this.init()
    }

    init () {
        this.toggleBookmark()
        this.toggleButtonAdd()
        this.getBookmarkButtons()
        this.showMore()
        this.removeBookmarkByRemoveButton()
    }


    toggleBookmark () {
        const bookmarkButton = document.querySelector<HTMLButtonElement>('.navigation__item--bookmark')
        const bookmarkButtonIcon = document.querySelector<HTMLElement>('.icon--bookmark')
        const bookmarkSidebar = document.querySelector<HTMLElement>('.bookmarks__sidebar')
        const closeBookmarks = document.querySelector<HTMLButtonElement>('#bookmarksClose')


        bookmarkButton.addEventListener('click', ()=> {
            bookmarkSidebar.classList.remove('d-none')
            bookmarkSidebar.classList.add('bookmarks__sidebar--expanded')
            bookmarkButton.classList.add('background--blur')

            // close the bookmark sidebar through the ESC
            if (bookmarkSidebar.classList.contains('bookmarks__sidebar--expanded')) {
                document.addEventListener('keydown', e => {
                    // ESC
                    if (e.key === "Escape") {
                        bookmarkSidebar.classList.remove('bookmarks__sidebar--expanded')
                        bookmarkButton.classList.remove('background--blur')
                        bookmarkButton.focus() // leave focus on the button
                    }
                })
            }
        })

        closeBookmarks.addEventListener('click', () => {
            bookmarkSidebar.classList.remove('bookmarks__sidebar--expanded')
            bookmarkButton.classList.remove('background--blur')
            bookmarkSidebar.classList.add('d-none')
        })

        window.addEventListener('click', e => {
            if (e.target !== bookmarkButtonIcon) {
                bookmarkSidebar.classList.remove('bookmarks__sidebar--expanded')
                bookmarkButton.classList.remove('background--blur')
            }
        })

        bookmarkSidebar.addEventListener('click', e => {
            e.stopPropagation()
        })
    }

    toggleButtonAdd () {
        const addBookmarkButtons = document.querySelectorAll('.button--bookmark-add')

        addBookmarkButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const buttonInner = e.target

                // @ts-ignore
                if(buttonInner.classList.contains('icon--bookmark-add')) {
                    // @ts-ignore
                    buttonInner.classList.remove('icon--bookmark-add')
                    // @ts-ignore
                    buttonInner.classList.add('icon--bookmark-delete')
                    // @ts-ignore
                    buttonInner.innerText = 'Seite von Merkliste entfernen'
                } else {
                    // @ts-ignore
                    buttonInner.classList.remove('icon--bookmark-delete')
                    // @ts-ignore
                    buttonInner.classList.add('icon--bookmark-add')
                    // @ts-ignore
                    buttonInner.innerText = 'Seite merken'
                }
            })
        })
    }

    // dummy code für TYPO3 Plugin soll angepasst sein
    getBookmarkButtons () {
        let allBookmarkButtons = document.querySelectorAll('[data-bookmark-entity]')

        allBookmarkButtons.forEach(bookmarkButton => {
            bookmarkButton.addEventListener('click', () => {
                // @ts-ignore
                let bookmarkStatus = bookmarkButton.dataset.bookmarkAdded

                let data = {
                    // @ts-ignore
                    'uid': bookmarkButton.dataset.bookmarkUid,
                    // @ts-ignore
                    'entity': bookmarkButton.dataset.bookmarkEntity
                }

                if(bookmarkStatus === 'true') {
                    console.log('true', data)
                }

            })
        })
    }

    // dummy code für TYPO3 Plugin soll angepasst sein
    getAllBookmarkedEntities () {
        const bookmarkStorage = localStorage.getItem('myBookmarks')
        // const url = window.location.href

        const data = {
            'tx_xmdkfz_bookmarklist[bookmarkData]': bookmarkStorage
        }

        async function postData(url: string = 'https://www.dkfz.de/de/index.html', bookmarkStorage = {}) {
            const response = await fetch(url, {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                referrerPolicy: 'no-referrer',
                body: JSON.stringify(data)
            });
            return response;
        }

        postData('https://www.dkfz.de/de/index.html', { body: bookmarkStorage })
            .then(data => {
                console.log(data);
            });
    }

    // show more element; bei Plugin in TYPO3 soll angepasst sein
    showMore() {
        const bookmarkList = document.querySelectorAll('.bookmarks__sidebar-list')
        let currentItem = 3

        bookmarkList.forEach(list => {
            const itemsList = list.querySelectorAll<HTMLElement>('.bookmarks__sidebar-items')
            let itemsPage = [...itemsList]
            let loadMoreButton = list.querySelector<HTMLButtonElement>('.button--more')

            // state of button first
            if(currentItem >= itemsPage.length) {
                // @ts-ignore
                loadMoreButton.style.display = 'none'
            }

            loadMoreButton.addEventListener('click', () => {
                let itemsPage = [...itemsList]

                for (let i = currentItem; i < currentItem + 3; i++) {
                    itemsPage[i].classList.add('show')
                }
                currentItem += 3;

                if(currentItem >= itemsPage.length) {
                    // @ts-ignore

                    loadMoreButton.classList.add('hide')
                }
            })
        })
    }

    // remove Element; bei Plugin in TYPO3 soll angepasst sein
    removeBookmarkByRemoveButton() {
        const removeBookmarkButtons = document.querySelectorAll('.bookmarks__sidebar-items .button--remove')

        removeBookmarkButtons.forEach(button => {

            button.addEventListener('click', e => {
                e.stopImmediatePropagation()
                const buttonPar = button.parentElement
                buttonPar.parentElement.style.display = 'none'
            })
        })
    }
}

/**
 *     @section 4. Export class
 */

export default (new Bookmarks())

// end of bookmarks.js
