import app from './basic'
import {LightboxStyle} from './lightbox';

class Bookmarks {
  constructor() {
    this.init()
  }

  protected init() {
    this.bindEvents()
  }

  protected bindEvents() {
    const bookmarkButton = document.querySelector<HTMLButtonElement>('.navigation__item--bookmark')

    if (!bookmarkButton) {
      return
    }

    bookmarkButton.addEventListener('click', this.onBookmarkButtonClick.bind(this))
  }

  protected onBookmarkButtonClick(e: Event) {
    e.preventDefault()
    app.lightbox.open(LightboxStyle.sidebar)
  }
}

export default (new Bookmarks())
