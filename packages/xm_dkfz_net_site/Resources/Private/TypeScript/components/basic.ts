import Lightbox from './lightbox';

export default {

  scrollbarWidth: window.innerWidth - document.documentElement.clientWidth,
  transitionTime: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--transition-time')),
  lightbox: new Lightbox(),

  inViewport(checkEl: Element, targetForCssClassEl: Element = checkEl, cssClass: string = 'fx--visible', once: boolean = false) {
    const app = this

    // observe
    new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {

          if (targetForCssClassEl === checkEl) {
            entry.target.classList.add(cssClass)
          } else {
            targetForCssClassEl.classList.add(cssClass)
          }

          // create custom: reset timeout
          const event = new Event('viewport:in', {bubbles: true})
          entry.target.dispatchEvent(event)
        } else {

          if (!once) {
            if (targetForCssClassEl === checkEl) {
              entry.target.classList.remove(cssClass)
            } else {
              targetForCssClassEl.classList.remove(cssClass)
            }
          }

          // create custom: reset timeout
          const event = new Event('viewport:out', {bubbles: true})
          entry.target.dispatchEvent(event)
        }
      })
    }, {
      // root: document,
      rootMargin: '0px 0px 0px 0px',
      threshold: 0
    }).observe(checkEl)
  },

  apiRequest: async function (url: string, method: string = 'GET', form: HTMLFormElement = null): Promise<any> {

    let initConf = Object({method: method});

    if (form) {
      initConf['body'] = new FormData(form);
    }

    return fetch(url, initConf)
      .then(response => {
        if (!response.ok) {
          this.handleRequestError(response)
          return
        }
        return response.json()
      })
      .catch(error => {
        this.handleRequestError(error)
      })
  },

  handleRequestError: function (error: any) {
    localStorage.removeItem('userinfo');
    console.error('could not load data', error)
  }

}
