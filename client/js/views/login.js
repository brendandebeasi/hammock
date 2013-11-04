/*global define*/
define([
    'zepto',
    'underscore',
    'backbone',
    'text!templates/login.html'
], function ($, _, Backbone,LoginTemplate) {
    'use strict';

    var LoginView = Backbone.View.extend({

        el: '#container',
        template: _.template(LoginTemplate),
        events: {
            'click button.login': 'attemptLogin',
            'keypress input': 'filterKey'
        },
        initialize: function (options) {
            $('body').addClass('hamLogin');
            this.render();
        },
        render: function () {
            this.$el.html(this.template());
            this.$('.login').focus();
            return this;
        },
        destroy: function() {
            $('body').removeClass('hamLogin');
        },
        filterKey: function(e) {
            if(e.keyCode == 13) this.attemptLogin();
        },
        attemptLogin: function() {
            this.$('button.login').addClass('hide');
            var loginData = {};
            this.$('input').each(function(i,el) {
                var inputVal = $(el).val().trim();
                if(inputVal == '') $(el).addClass('invalid');
                else {
                    $(el).removeClass('invalid');
                    loginData[$(el).attr('name')] = $(el).val();
                }
            });
            if(this.$('input.invalid').length < 1) {
                Ham.session.authenticate(loginData['login'],loginData['password']);
            }
        }
    });

    return LoginView;
});
