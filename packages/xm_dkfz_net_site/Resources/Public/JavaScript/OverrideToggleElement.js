define(['jquery'], function ($) {

    return function () {

        this.$checkbox = null;
        this.$nextTcaElement = null;

        this.init = function(inputName) {

            // cache elements
            this.$checkbox = $('input[type="checkbox"][data-formengine-input-name="' + inputName + '"]');
            this.$nextTcaElement = this.$checkbox.closest('.form-section').next();

            // initial display
            this.togglePalette();

            // bind event
            this.$checkbox.on('change', () => {
                this.togglePalette();
            });
        }

        this.togglePalette = function() {
            const doShow = parseInt($('input[type="hidden"][name="' + this.$checkbox.attr('data-formengine-input-name') + '"]').val());

            if (doShow) {
                this.$nextTcaElement.show();
            } else {
                this.$nextTcaElement.hide();
            }
        }

    }

});
