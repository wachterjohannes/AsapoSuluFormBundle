require.config({
    paths: {
        asaposuluform: '../../asaposuluform/js',
        "type/client-select": '../../asaposuluform/js/validation/types/client-select'
    }
});

define({

    name: 'AsapoSuluFormBundle',

    initialize: function(app) {

        'use strict';

        app.components.addSource('asaposuluform', '/bundles/asaposuluform/js/components');

        app.sandbox.mvc.routes.push({
            route: 'form/:entityName',
            callback: function(entityName) {
                this.html(
                    '<div data-aura-component="form-entry@asaposuluform" data-aura-entity-name="' + entityName + '"/>'
                );
            }
        });
    }
});
