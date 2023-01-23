import Lightbox from './lightbox'
import Notice from './notice'

export default {
  scrollbarWidth: window.innerWidth - document.documentElement.clientWidth,
  transitionTime: parseInt(getComputedStyle(document.documentElement).getPropertyValue('--transition-time')),
  lightbox: new Lightbox(),
  notice: new Notice(),

  inViewport(checkEl: Element, targetForCssClassEl: Element = checkEl, cssClass = 'fx--visible', once = false) {
    // observe
    new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            if (targetForCssClassEl === checkEl) {
              entry.target.classList.add(cssClass)
            } else {
              targetForCssClassEl.classList.add(cssClass)
            }

            // create custom: reset timeout
            const event = new Event('viewport:in', { bubbles: true })
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
            const event = new Event('viewport:out', { bubbles: true })
            entry.target.dispatchEvent(event)
          }
        })
      },
      {
        // root: document,
        rootMargin: '0px 0px 0px 0px',
        threshold: 0
      }
    ).observe(checkEl)
  },

  apiRequest: async function (url: string, method = 'GET', form: HTMLFormElement | null = null): Promise<any> {
    const initConf = Object({ method })

    if (form) {
      initConf.body = new FormData(form)
    }

    return await fetch(url, initConf)
      .then(async response => {
        if (!response.ok) {
          this.handleRequestError()
          if (response.status === 403) {
            this.showLogin()
          }
          return
        }
        return await response.json()
      })
      .catch(() => {
        this.handleRequestError()
      })
  },

  handleRequestError: function () {
    localStorage.removeItem('userinfo')
    // console.error('could not load data', error)
  },

  showLogin: function () {
    const loginFormEl = document.querySelector('#hiddenLogin')
    const loginFormHtml = loginFormEl ? loginFormEl.outerHTML : 'Login form not found'

    this.lightbox.displayContent(loginFormHtml)
    this.lightbox.stopLoading()
    this.lightbox.open()
  }
}
