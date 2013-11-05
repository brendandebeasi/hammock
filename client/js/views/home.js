/*global define*/
define([
    'zepto',
    'underscore',
    'backbone',
    'text!templates/app_base.html',
    'text!templates/home.html'
], function ($, _, Backbone,AppBaseTemplate,HomeTemplate) {
    'use strict';

    var HomeView = Backbone.View.extend({

        el: '#container',
        template: _.template(HomeTemplate),
        appTemplate: _.template(AppBaseTemplate),
        events: {
        },
        initialize: function (options) {
            $('body').addClass('hamHome');
            this.$el.html(this.appTemplate());
            this.render();
        },
        destroy: function() {
            $('body').removeClass('hamHome');
        },
        render: function () {
            this.$el.html(this.template({
                userFirstName: Ham.session.get('user').get('first_name')
            }));
            return this;
        },
        filterKey: function(e) {
//            if(e.keyCode == 13) this.attemptLogin();
        },
        attemptLogin: function() {

        }
    });

    return HomeView;
});
