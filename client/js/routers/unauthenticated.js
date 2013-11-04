/*global define*/
define([
    'zepto',
    'backbone',
    'views/login',
    'views/signup'
], function ($, Backbone, LoginView, SignupView) {
    'use strict';

    var AuthenticatedRouter = Backbone.Router.extend({
        initialize: function() {
        },
        routes: {
            ''                      : 'login',
            'signup'                : 'signup'
        },
        login: function() {
            Ham.removeCurrentViews();
            Ham.set('currentViews',[new LoginView()]);
        },
        signup: function() {
            Ham.removeCurrentViews();
            Ham.set('currentViews',[new SignupView()]);
        }
    });

    return AuthenticatedRouter;
});
