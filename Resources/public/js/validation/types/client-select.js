/*
 * This file is part of the Husky Validation.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

define([
    'type/default',
    'form/util'
], function(Default) {

    'use strict';

    var dataChangedHandler = function(data, $el) {
        App.emit('sulu.preview.update', $el, data);
        App.emit('sulu.content.changed');
    };

    return function($el, options) {
        var defaults = {
                id: 'id',
                label: 'value',
                required: false
            },

            typeInterface = {
                initializeSub: function() {
                    var deselectHandler = 'husky.select.' + options.instanceName + '.deselected.item',
                        selectHandler = 'husky.select.' + options.instanceName + '.selected.item';

                    App.off(deselectHandler, dataChangedHandler);
                    App.off(selectHandler, dataChangedHandler);
                    App.on(deselectHandler, dataChangedHandler);
                    App.on(selectHandler, dataChangedHandler);
                },

                setValue: function(data) {

                    if (data === undefined || data === '' || data === null) {
                        return;
                    }

                    var selectionValues = [],
                        selection = [];

                    for (var i = 0, len = data.length; i < len; i++) {
                        selectionValues.push(data[i][this.options.id]);
                        selection.push(data[i][this.options.label]);
                    }
                    this.$el.data({
                        'selection': selection,
                        'selectionValues': selectionValues
                    }).trigger('data-changed');
                },

                getValue: function() {
                    // For single select
                    var data = [],
                        ids = this.$el.data('selection'),
                        values = this.$el.data('selection-values');

                    if (!ids || ids.length === 0) {
                        return [];
                    }

                    for (var i = 0, len = ids.length; i < len; i++) {
                        var item = {};
                        item[this.options.label] = values[i];
                        item[this.options.id] = ids[i];

                        data.push(item);
                    }

                    return data;
                },

                needsValidation: function() {
                    var val = this.getValue();
                    return !!val;
                },

                validate: function() {
                    var value = this.getValue();
                    if (typeof value === 'object' && value.hasOwnProperty('id')) {
                        return !!value.id;
                    } else {
                        return value !== '' && typeof value !== 'undefined';
                    }
                }
            };

        return new Default($el, defaults, options, 'husky-select', typeInterface);
    };
});
