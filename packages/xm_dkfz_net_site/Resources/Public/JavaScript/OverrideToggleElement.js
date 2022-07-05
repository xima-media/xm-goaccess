define(['jquery'], function ($) {

    const OverrideToggleElement = {

        $checkbox: null,
        $palette: null,

        init: (inputName) => {

            // cache elements
            this.$checkbox = $('input[type="checkbox"][data-formengine-input-name="' + inputName + '"]');
            this.$palette = this.$checkbox.closest('.form-section').next();

            // initial display
            OverrideToggleElement.togglePalette();

            // bind event
            this.$checkbox.on('change', () => {
                console.log('change')
                OverrideToggleElement.togglePalette();
            });
        },

        togglePalette: () => {
            const doShow = parseInt($('input[type="hidden"][name="' + this.$checkbox.attr('data-formengine-input-name') + '"]').val());
            
            if (doShow) {
                this.$palette.show();
            } else {
                this.$palette.hide();
            }
        }

    }

    return OverrideToggleElement;
});
