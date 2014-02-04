define([
    'jquery',
    'underscore',
    'backbone',
    'text!modulesSrc/contactForm/templates/contactForm.html'
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

            var Error = false;

            if (!jQuery('input[name="z_name"]').val()) {
                jQuery('input[name="z_name"]').addClass('error');
                Error = true;
            }
            if (!jQuery('input[name="z_requester"]').val()) {
                jQuery('input[name="z_requester"]').addClass('error');
                Error = true;
            }
            if (!Error) {
            var data = jQuery('#contactForm').serialize();
            jQuery.post('modules/contactForm/ajax/former.php', data);
            this.$el.modal( 'hide' );
            }
        }

    });
    return FormView;
});