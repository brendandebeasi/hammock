define([
    'zepto',
    'underscore',
    'backbone',
    'routers/authenticated',
    'routers/unauthenticated',
    'models/session'
], function(
    $,
    _,
    Backbone,
    AuthenticatedRouter,
    UnauthenticatedRouter,
    SessionModel
    ){
    "use strict";
    var Ham = Backbone.Model.extend({
        api: '/',//PROD
        session: null,
        initialize: function() {
            window.Ham= this;

            //setup ajax load indicators
            $(document).on('ajaxStart',window.Ham.showLoader);
            $(document).on('ajaxStop',window.Ham.hideLoader);
            //setup session
            this.session = new SessionModel();
            this.session.on('deauthenticated',this.startUnauthRouter,this);
            this.session.on('authenticated',this.startAuthRouter,this);
            this.session.restoreFromLocalStore();
        },
        showLoader: function() {
            $('#loadIndicator').animate({opacity: 1},'slow');
        },
        hideLoader: function() {
            $('#loadIndicator').animate({opacity: 0},'fast');
        },
        startHistory: function() {
            if(typeof Backbone.history.root == 'undefined') Backbone.history.start();
        },
        startAuthRouter: function() {
            this.router = new AuthenticatedRouter();
            this.startHistory();
        },
        startUnauthRouter: function() {
            this.router = new UnauthenticatedRouter();
            this.startHistory();
        },
        navigate: function(uri) {
            this.router.navigate(uri, {trigger: true});
        },
        removeView: function(view) {
            view.destroy();
            view = null;
        },
        removeCurrentViews: function () {
            var currentViews = this.get('currentViews');
            if(currentViews != undefined) {
                for(var i=0;i<currentViews.length;i++) {
                    this.removeView(currentViews[i]);
                }
            }
        }


    });

    return Ham;
});