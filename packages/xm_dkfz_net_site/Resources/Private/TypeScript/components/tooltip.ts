import app from './basic'
import tippy from 'tippy.js'

class Tooltip {
  constructor() {
    document.querySelectorAll<HTMLElement>('[data-tooltip]').forEach(tooltip => {
      const placement = tooltip.dataset.tooltipPlacement ? tooltip.dataset.tooltipPlacement : 'bottom'
      const content = tooltip.dataset.tooltip
      let showOnCreate = tooltip.dataset.tooltipShowOnCreate ? tooltip.dataset.tooltipShowOnCreate : false

      // init tooltip
      // @ts-expect-error
      tippy(tooltip, {
        interactive: true,
        trigger: 'mouseenter focus',
        placement,
        appendTo: document.body,
        content,
        delay: [app.transitionTime, 0],
        touch: false,
        showOnCreate,
        onShow(instance) {
          if (showOnCreate) {
            // hide after 3 seconds
            setTimeout(() => {
              instance.hide()
            }, 3000)

            // set variable to false to stay at hover
            showOnCreate = false
          }
        }
      })
    })
  }
}

export default new Tooltip()
