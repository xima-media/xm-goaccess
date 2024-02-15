define(['jquery', 'TYPO3/CMS/Dashboard/Contrib/chartjs', 'TYPO3/CMS/Core/Ajax/AjaxRequest', 'TYPO3/CMS/Core/Ajax/AjaxResponse'], function ($, chartjs, AjaxRequest, AjaxResponse) {

  const PageHeaderChart = {


    init: () => {

      const chartWrapper = document.querySelector('.dashboard-item')

      let isLoaded = false
      const button = document.querySelector('.goaccess-button')
      button.addEventListener('click', (e) => {
        chartWrapper.classList.toggle('hidden')
        button.classList.toggle('active')
        button.blur()
        if (!isLoaded) {
          PageHeaderChart.createChart()
          isLoaded = true
        }
      })

    },

    createChart: () => {
      const canvas = document.querySelector('.dashboard-item canvas')
      const pageUid = parseInt(canvas.getAttribute('data-page-uid'))

      new AjaxRequest(TYPO3.settings.ajaxUrls.goaccess_page).withQueryArguments({pid: pageUid}).get().then(async function (AjaxResponse) {
        const data2 = await AjaxResponse.resolve()

        new Chart(
          canvas,
          {
            options: {
              legend: {
                display: true
              },
              tooltips: {
                mode: 'index'
              },
              scales: {
                yAxes: [
                  {
                    display: 'auto',
                    ticks: {
                      beginAtZero: true
                    }
                  },
                  {
                    id: 'right',
                    position: 'right',
                    display: 'auto',
                    ticks: {
                      beginAtZero: true,
                      sampleSize: 4,
                      autoSkip: true
                    }
                  }
                ],
                xAxes: [
                  {}
                ]
              }
            },
            type: 'line',
            data: {
              labels: data2.labels,
              datasets: data2.datasets
            }
          }
        );
      })
    }

  }

  return PageHeaderChart.init();
});
