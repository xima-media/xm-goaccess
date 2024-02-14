define(['jquery', 'TYPO3/CMS/Core/Event/RegularEvent'], function ($, RegularEvent) {

	const MappingsList = {

		init: () => {
			const demandForm = document.querySelector('.demand-form')
			demandForm.addEventListener('input', (e) => {
				demandForm.submit();
			})
		},

	}

	return MappingsList.init();
});
