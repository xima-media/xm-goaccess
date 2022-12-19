define(['TYPO3/CMS/Core/DocumentService'], function (DocumentService) {

    const ManualGlobal = {

        currentModule: '',

        init: () => {
            DocumentService.ready().then(() => {
                ManualGlobal.bindListener();
            });
        },

        bindListener: () => {

            top.document.getElementById('typo3-contentIframe').addEventListener('load', function () {

                const tree = top.document.querySelector('typo3-backend-navigation-component-pagetree');
                const isManualModule = top.window.location.pathname === '/typo3/module/help/XmManualManual'

                if (!isManualModule && tree && tree.classList.contains('filtered-for-manuals')) {
                    tree.classList.remove('filtered-for-manuals');
                    tree.refresh();
                }
            });
        },
    }

    return ManualGlobal.init();
});
