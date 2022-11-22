import './jobs.scss'

class Jobs {
  constructor() {
    this.bindEvents()
  }

  bindEvents() {
    document.querySelectorAll('.jobs__nav__button').forEach((btn) => {
      btn.addEventListener('click', this.onMoreButtonClick.bind(this));
    });

    document.querySelectorAll('.job__search-select').forEach(select => {
      select.addEventListener('change', this.onSearchSelectChange.bind(this));
    })
  }

  onSearchSelectChange(e: Event) {
    e.preventDefault();
    const selectElement = e.currentTarget as HTMLSelectElement;
    const filterFor = selectElement.getAttribute('id');
    const filterValue = selectElement.value;

    document.querySelectorAll('.jobs--list a').forEach(link => {
      link.classList.remove('hidden-filter-' + filterFor);
      const linkValue = link.getAttribute('data-' + filterFor);
      if (filterValue && linkValue !== filterValue) {
        link.classList.add('hidden-filter-' + filterFor);
      }
    })

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
