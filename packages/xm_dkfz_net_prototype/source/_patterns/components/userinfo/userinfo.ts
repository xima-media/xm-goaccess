import app from '../basic/basic'

interface UserData {
  uid: number,
  username: string,
  logo: string,
  url: string
}

interface UserOffer {
  uid: number,
  title: string
}

interface UserBookmark {
  uid: number,
  record_type: string,
  foreign_uid: number
}

interface Userinfo {
  user: UserData
  offers: Array<UserOffer>,
  bookmarks: Array<UserBookmark>
}

class Userinfo {

  protected userinfo: Userinfo;

  constructor() {
    app.log('component "userinfo" loaded')

    if (!document.querySelectorAll('#userinfoUri').length) {
      return
    }

    this.loadUserinfo().then(r => {
      this.modifyUserNav();
    });

    this.bindEvents()
  }

  protected bindEvents() {
    this.bindBookmarkLinks()
  }

  protected bindBookmarkLinks() {
    document.querySelectorAll('button[data-bookmark-url]').forEach((button) => {
      button.addEventListener('click', this.onBookmarkLinkClick.bind(this));
    })
  }

  protected onBookmarkLinkClick(e: Event)
  {
    e.preventDefault()
    const button = e.currentTarget as Element;
    const url = button.getAttribute('data-bookmark-url');
    const method = button.classList.contains('button--primary') ? 'DELETE' : 'POST';
    this.apiRequest(url, method).then(() => {
      button.classList.toggle('fx--hover')
      button.classList.toggle('js--checked')
    });
  }

  protected modifyUserNav() {
    const userLinkElement = document.querySelector('[data-user-profile-link]');
    if (!userLinkElement || !this.userinfo) {
      return;
    }
    userLinkElement.setAttribute('href', this.userinfo.user.url)
  }

  protected async loadUserinfo() {
    const loadedFromStorage = this.loadUserinfoFromStorage();
    if (!loadedFromStorage) {
      return await this.requestUserinfo();
    }
  }

  protected loadUserinfoFromStorage(): boolean {
    const storedUserinfo = localStorage.getItem('userinfo');
    if (!storedUserinfo) {
      return false;
    }
    this.userinfo = JSON.parse(storedUserinfo);
    return true;
  }

  protected async requestUserinfo() {
    const url = document.querySelector('#userinfoUri').getAttribute('data-user-info')

    if (!url) {
      return
    }

    return this.apiRequest(url).then((userinfo) => {
      this.userinfo = userinfo
      localStorage.setItem('userinfo', JSON.stringify(userinfo));
    });
  }

  protected handleRequestError(error: any) {
    console.error('could not load user data', error)
  }

  protected async apiRequest(url: string, method: string = 'GET'): Promise<Userinfo> {
    return fetch(url, {
      method: method
    })
      .then(response => {
        if (!response.ok) {
          this.handleRequestError(response)
        }
        return response.json()
      })
      .catch(error => {
        this.handleRequestError(error)
      })
  }


}

export default (new Userinfo())
