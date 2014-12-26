define([
    'asaposuluform/model/entry'
], function(Entry) {

    'use strict';

    return {

        layout: {
            content: {
                width: 'max',
                leftSpace: false,
                rightSpace: false
            },
            sidebar: {
                width: 'fixed',
                cssClasses: 'sidebar-padding-50'
            }
        },

        header: {
            title: '???',
            noBack: true,

            breadcrumb: [
                {title: '???'},
                {title: '???'}
            ]
        },

        initialize: function() {
            this.bindCustomEvents();

            this.render();
        },

        bindCustomEvents: function() {
            // navigate to edit contact
            this.sandbox.on('husky.datagrid.item.click', function(item) {
                this.sandbox.emit(
                    'sulu.sidebar.set-widget', '/admin/widget-groups/form-entry-' + this.options.entityName + '?entry=' + item
                );
            }, this);

            // delete clicked
            this.sandbox.on('sulu.list-toolbar.delete', function() {
                this.sandbox.emit('husky.datagrid.items.get-selected', function(ids) {
                    this.delEntries(ids);
                }.bind(this));
            }, this);
        },

        render: function() {
            var $list = this.sandbox.dom.createElement('<div id="list-container"/>');
            this.sandbox.dom.html(this.$el, $list);

            // init list-toolbar and datagrid
            this.sandbox.sulu.initListToolbarAndList.call(this,
                'formFiels' + this.options.entityName,
                '/admin/api/forms/' + this.options.entityName + '/entry/fields',
                {
                    el: this.$find('#list-toolbar-container'),
                    instanceName: 'contactentries',
                    inHeader: true,
                    parentTemplate: null,
                    template: [
                        {
                            id: 'delete',
                            icon: 'trash-o',
                            title: 'delete',
                            position: 10,
                            disabled: false,
                            callback: function() {
                                this.sandbox.emit('sulu.list-toolbar.delete');
                            }.bind(this)
                        },
                        {
                            id: 'settings',
                            icon: 'gear',
                            position: 20,
                            items: [
                                {
                                    title: this.sandbox.translate('sulu.list-toolbar.export'),
                                    disabled: true
                                },
                                {
                                    type: 'columnOptions'
                                }
                            ]
                        }
                    ]
                },
                {
                    el: $list,
                    url: '/admin/api/forms/' + this.options.entityName + '/entries',
                    searchInstanceName: 'formentries',
                    searchFields: ['???'],
                    resultKey: 'entries',
                    viewOptions: {
                        table: {
                            highlightSelected: true,
                            fullWidth: true
                        }
                    }
                }
            );
        },

        delEntries: function(ids) {
            if (ids.length < 1) {
                this.sandbox.emit('sulu.dialog.error.show', 'No form-entry selected for deletion');
                return;
            }
            this.confirmDeleteDialog(function(wasConfirmed) {
                if (wasConfirmed) {
                    ids.forEach(function(id) {
                        var entry = new Entry({id: id});
                        entry.destroy({
                            success: function() {
                                this.sandbox.emit('husky.datagrid.record.remove', id);
                            }.bind(this)
                        });
                    }.bind(this));
                }
            }.bind(this));
        },

        /**
         * @var ids - array of ids to delete
         * @var callback - callback function returns true or false if data got deleted
         */
        confirmDeleteDialog: function(callbackFunction) {
            // check if callback is a function
            if (!!callbackFunction && typeof(callbackFunction) !== 'function') {
                throw 'callback is not a function';
            }
            // show dialog
            this.sandbox.emit('sulu.overlay.show-warning',
                'sulu.overlay.be-careful',
                'sulu.overlay.delete-desc',
                callbackFunction.bind(this, false),
                callbackFunction.bind(this, true)
            );
        }
    };
});
