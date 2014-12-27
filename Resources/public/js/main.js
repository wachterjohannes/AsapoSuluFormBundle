require.config({
    paths: {
        asaposuluform: '../../asaposuluform/js'
    }
});

define({

    name: 'AsapoSuluFormBundle',

    initialize: function(app) {

        'use strict';

        app.components.addSource('asaposuluform', '/bundles/asaposuluform/js/components');

        app.sandbox.mvc.routes.push({
            route: 'form/:formName',
            callback: function(formName) {
                this.html(
                    '<div data-aura-component="form-entry@asaposuluform" data-aura-form-name="' + formName + '"/>'
                );
            }
        });
    }
});
