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

                const currentModule = top.TYPO3.ModuleMenu.App.loadedModule;

                if (ManualGlobal.currentModule === 'help_XmManualManual' && currentModule !== 'help_XmManualManual') {
                    const tree = top.document.querySelector('typo3-backend-navigation-component-pagetree');
                    if (tree) {
                        tree.classList.remove('filtered-for-manuals');
                        tree.refresh();
                    }
                }

                ManualGlobal.currentModule = currentModule;
            });
        },
    }

    return ManualGlobal.init();
});
