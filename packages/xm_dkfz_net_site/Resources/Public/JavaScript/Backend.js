define(['jquery'], function($) {

    // Container Accordion Toggle
    $('.container-accordion-title').on('click', (e) => {
        $(e.currentTarget).toggleClass('container-accordion--open');
    });
});
