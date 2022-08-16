import app from '../basic/basic'
import './disruptor.scss'

class Disruptor {

  constructor() {
    app.log('component "disruptor" loaded')

    this.bindDisruptorLoad()
  }

  protected bindDisruptorLoad() {
    document.addEventListener('DOMContentLoaded', () => {

      const disruptorModal = document.querySelector('.disruptor-wrapper')
      const disruptorModalFixed = document.querySelector('.disruptor-wrapper--fixed')
      const currentDate = Date.now()


      if(disruptorModal && localStorage.getItem('disruptor') === null) {
        app.lightbox.startLoading()
        app.lightbox.open(2, 'disruptor')

        app.lightbox.displayContent(disruptorModal.innerHTML)

        // set currentTime to LocalStorage
        // localStorage.setItem("currentDate", JSON.stringify(currentDate));

        document.addEventListener('lightboxClose', e => {
          const target = e.target as HTMLButtonElement
          const root = target.querySelector('html')

          if (root.dataset.lightBoxType && root.dataset.lightBoxType === 'disruptor' ) {
            disruptorModalFixed.classList.remove('d-none')

            // set LocalStorage
            // const dateSet = new Date()
            // dateSet.setDate(dateSet.getDate() + 1)
            // dateSet.setMinutes(dateSet.getMinutes() + 1)
            // localStorage.setItem("disruptor", JSON.stringify(dateSet));
            localStorage.setItem("disruptor", String(currentDate));
          }
        })

        app.lightbox.stopLoading()
      } else if(localStorage.getItem('disruptor') !== null){
        app.lightbox.setStyle(2)
        disruptorModalFixed.classList.remove('d-none')
      }

    });

  }







}

export default (new Disruptor())
