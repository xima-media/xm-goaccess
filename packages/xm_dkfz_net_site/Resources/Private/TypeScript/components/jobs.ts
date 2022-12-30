class Jobs {
  constructor() {
    this.bindEvents()
    this.initPagination()
  }

  initPagination() {
    document.querySelectorAll('.jobs--list--pager-not-loaded').forEach(div => div.classList.remove('jobs--list--pager-not-loaded'))
    this.updatePager()
  }

  updatePager() {
    document.querySelectorAll('.jobs--list').forEach(list => {
      list.querySelectorAll('a').forEach(a => a.classList.add('hidden-pager'))
      const notHiddenElements = list.querySelectorAll('a:not(.hidden-filter-category):not(.hidden-filter-place)')
      notHiddenElements.forEach(a => a.classList.remove('hidden-pager'))

      const count = notHiddenElements.length
      const pageNr = parseInt(list.getAttribute('data-page'))
      const visibleCount = 8 * pageNr
      notHiddenElements.forEach((element, i) => {
        if (i > visibleCount) {
          element.classList.add('hidden-pager')
        }
      })

      // update counter
      document.querySelector('span[data-job-count]').innerHTML = visibleCount.toString()
      document.querySelector('span[data-all-job-count]').innerHTML = count.toString()

      // hide pager
      if (visibleCount >= count) {
        list.setAttribute('data-page', '-1')
      }

      // hide + show not found message
      if (notHiddenElements.length === 0) {
        list.setAttribute('data-page', '-2')
      }
    })
  }

  bindEvents() {
    document.querySelectorAll('.jobs__nav__button').forEach(btn => {
      btn.addEventListener('click', this.onMoreButtonClick.bind(this))
    })

    document.querySelectorAll('.job__search-select').forEach(select => {
      select.addEventListener('change', this.onSearchSelectChange.bind(this))
    })
  }

  onSearchSelectChange(e: Event) {
    e.preventDefault()
    const selectElement = e.currentTarget as HTMLSelectElement
    const filterFor = selectElement.getAttribute('id')
    const filterValue = selectElement.value

    document.querySelectorAll('.jobs--list').forEach(list => list.setAttribute('data-page', '1'))

    document.querySelectorAll('.jobs--list a').forEach(link => {
      link.classList.remove('hidden-filter-' + filterFor)
      const linkValue = link.getAttribute('data-' + filterFor)
      if (filterValue && linkValue !== filterValue) {
        link.classList.add('hidden-filter-' + filterFor)
      }
    })

    this.updatePager()
  }

  onMoreButtonClick(e: Event) {
    e.preventDefault()
    document.querySelectorAll('.jobs--list').forEach(list => {
      const pageNr = parseInt(list.getAttribute('data-page')) + 1
      document.querySelector('span[data-job-count]').innerHTML = (pageNr * 8).toString()
      list.setAttribute('data-page', pageNr.toString())
    })

    this.updatePager()
  }
}

export default new Jobs()
