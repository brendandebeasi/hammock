/*global require*/
'use strict';

// Require.js allows us to configure shortcut alias
require.config({
    // The shim config allows us to configure dependencies for
    // scripts that do not call define() to register a module
    baseUrl: 'client/js',
    shim: {
        underscore: {
            exports: '_'
        },
        fineUploader: {
            deps: [
                'zepto'
            ]
        },
        backbone: {
            deps: [
                'underscore',
                'zepto'
            ],
            exports: 'Backbone'
        },
        backboneLocalStorage: {
            deps: ['backbone']
        },
        magnific: {
            deps: ['zepto']
        },
        zepto: {
            exports: '$'
        }
    },
    paths: {
        zepto: 'inc/zepto.min',
        underscore: 'inc/underscore.min',
        backbone: 'inc/backbone.min',
        backboneLocalStorage: 'inc/backbone-local-storage.min',
        text: 'inc/require-text.min',
        moment: 'inc/moment.min',
        magnific: 'inc/magnific.min',
//        fineUploader: 'inc/zepto.fineuploader-3.9.1.min',
    }
});

require([
    'backbone',
    'app'
], function (Backbone, Ham) {
    var ham = new Ham();
});