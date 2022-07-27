import './jobs.scss'

import app from '../basic/basic'

class Jobs {
  constructor() {
    app.log('component "job" loaded')
    this.bindEvents()
  }

  bindEvents() {
    document.querySelectorAll('.jobs__nav__button').forEach((btn) => {
      btn.addEventListener('click', this.onMoreButtonClick.bind(this));
    });
  }

  onMoreButtonClick(e: Event) {
    e.preventDefault();
    document.querySelectorAll('.jobs--list').forEach((list) => {
      let pageNr = parseInt(list.getAttribute('data-page')) + 1
      pageNr = parseInt(list.getAttribute('data-job-count')) <= pageNr * 8 ? -1 : pageNr
      document.querySelector('span[data-job-count]').innerHTML = (pageNr * 8).toString()
      list.setAttribute('data-page', pageNr.toString())
    });
  }
}

export default (new Jobs())
