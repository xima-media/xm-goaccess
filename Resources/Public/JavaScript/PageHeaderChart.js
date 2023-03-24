define(['jquery', 'TYPO3/CMS/Dashboard/Contrib/chartjs', 'TYPO3/CMS/Core/Ajax/AjaxRequest', 'TYPO3/CMS/Core/Ajax/AjaxResponse', 'TYPO3/CMS/Backend/Icons'], function ($, chartjs, AjaxRequest, AjaxResponse, Icons) {

	const PageHeaderChart = {

		init: () => {

			const canvas = document.querySelector('.dashboard-item canvas')

			if (!canvas) {
				return;
			}

			let onload = parseInt(canvas.getAttribute('data-onload'));
			const buttonClasses = 'btn btn-default btn-sm ' + (onload ? 'active' : '')

			const buttons = document.querySelector('.module-docheader-bar-buttons .module-docheader-bar-column-right .btn-toolbar')

			Icons.getIcon('action-chart', Icons.sizes.small).then(icon => {
				$('<a>')
					.html(icon)
					.attr({
						class: buttonClasses,
						href: '#',
						title: 'Display page performance'
					})
					.on('click', function (e) {
						e.preventDefault()
						// update class + state
						const button = e.target.closest('.btn')
						const active = button.classList.contains('active')
						if (active) {
							button.classList.remove('active')
						} else {
							button.classList.add('active')
						}
						new AjaxRequest(TYPO3.settings.ajaxUrls.goaccess_settings).post({pageHeaderChart: !active})

						// toggle chart
						if (active) {
							$(canvas).hide()
						} else {
							$(canvas).show()
						}

						if (!onload) {
							PageHeaderChart.renderChart()
							onload = 1
						}
					})
					.prependTo(buttons)
			})

			if (onload) {
				PageHeaderChart.renderChart();
			}
		},

		renderChart: () => {
			const canvas = document.querySelector('.dashboard-item canvas')

			if (!canvas) {
				return;
			}

			const pageUid = parseInt(canvas.getAttribute('data-page-uid'))

			if (!pageUid) {
				return;
			}

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
