define([
    'jquery',
    'underscore',
    'backbone',
    'text!modulesSrc/contactForm/templates/contactForm_bak.html'
], function ($, _, Backbone, FormTemplate) {
    var FormView = Backbone.View.extend({

        el: $("#form-wrapper"),

        initialize: function () {
            this.render();
        },

        render: function () {
            var compiledTemplate = _.template(FormTemplate, {}),
                that = this;
            this.$el.html(compiledTemplate);
            this.$el.modal( 'show' ).on( 'hide.bs.modal', function () {
                // reset trigger so that the modal can be shown more than once
                that.goTo( '', false );
            });

            var that = this;
            $( '#send_request' ).on( 'click', function () {
                that.send();
            });
        },

        send: function () {

            var data = jQuery('#contactForm').serialize();
            jQuery.post('modules/contactForm/ajax/magento-contact-form.php', data);
            this.$el.modal( 'hide' );

        }

    });
    return FormView;
});