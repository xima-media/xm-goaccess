import './news.scss'

import app from '../basic/basic'

class News {
  constructor() {
    app.log('component "news" loaded')
  }
}

export default (new News())
