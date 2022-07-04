define(['jquery'], function ($) {

    const Backend = {

        init: () => {
            Backend.bindListener();
        },

        bindListener: () => {
            // Container Accordion Toggle
            $('.container-accordion-title').on('click', (e) => {
                $(e.currentTarget).toggleClass('container-accordion--open');
            });
        }

    }

    return Backend.init();
});
