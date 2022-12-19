define(['TYPO3/CMS/Core/DocumentService'], function (DocumentService) {

    const ManualModule = {


        init: () => {
            DocumentService.ready().then(() => {

                top.TYPO3.Backend.NavigationContainer.showComponent('TYPO3/CMS/Backend/PageTree/PageTreeElement');

                setTimeout(function () {

                    const tree = top.document.querySelector('typo3-backend-navigation-component-pagetree');

                    if (tree && !tree.classList.contains('filtered-for-manuals')) {
                        tree.classList.add('filtered-for-manuals');
                        tree.refresh();
                    }

                }, 500);

            });
        },

    }

    return ManualModule.init();
});
