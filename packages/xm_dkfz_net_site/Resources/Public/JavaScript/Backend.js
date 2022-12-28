define(['jquery'], function ($) {

    const Backend = {

        init: () => {
            Backend.bindListener();
        },

        bindListener: () => {

            // Init accordions
            $('.container-accordion-settings').each(function (i, accordion) {
                Backend.initAccordion(accordion);
            })
        },

        initAccordion: (accordion) => {
            const table = $(accordion).closest('.exampleContent').find('table');
            const accordionItems = JSON.parse(accordion.getAttribute('data-accordion-items'));
            const icon = $(accordion).closest('.exampleContent').find('.accordion-container-start .chevron-icon');

            for (let i = 1; i <= accordionItems.length; i++) {
                const tr = $('tr:nth-child(' + i + ')', table);

                const a = $('<a />')
                    .addClass('container-accordion-title')
                    .attr('href', '#')
                    .html(icon.clone().html() + " " + accordionItems[(i - 1)])
                    .on('click', (e) => {
                        $(e.currentTarget).toggleClass('container-accordion--open');
                    });
                $('.t3-page-column-header', tr).replaceWith(a);

                tr.addClass('visible');
            }
        }

    }

    return Backend.init();
});
