define(['jquery', 'TYPO3/CMS/Core/Event/RegularEvent'], function ($, RegularEvent) {

	const DashboardList = {

		init: () => {
			new RegularEvent('widgetContentRendered', function (e, canvas) {
				e.preventDefault();

				canvas.querySelectorAll('a').forEach(link => {
					console.log(link)
				})

			}).delegateTo(document, '.dashboard-item[data-widget-key="goaccessRequests"]')
		},

	}

	return DashboardList.init();
});
