/*global define*/
define([
	'zepto',
	'backbone',
    'views/login'
], function ($, Backbone, LoginView) {
	'use strict';

	var AuthenticatedRouter = Backbone.Router.extend({
        initialize: function() {
        },
		routes: {
        },
        login: function() {
        }
	});

	return AuthenticatedRouter;
});
