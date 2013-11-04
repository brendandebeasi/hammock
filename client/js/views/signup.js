/*global define*/
define([
    'zepto',
    'underscore',
    'backbone',
    'text!templates/signup.html',
    'models/user'
], function ($, _, Backbone,SignupTemplate,UserModel) {
    'use strict';

    var SignupView = Backbone.View.extend({
        el: '#container',
        events: {
            'click button.signup': 'attemptSignup',
            'keypress input': 'filterKey'
        },
        template: _.template(SignupTemplate),
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
            if(e.keyCode == 13) this.attemptSignup();
        },
        attemptSignup: function() {
            this.$('button.signup').addClass('hide');
            var userData = {};
            this.$('input').each(function(i,el) {
                var inputVal = $(el).val().trim();
                if(inputVal == '') $(el).addClass('invalid');
                else {
                    $(el).removeClass('invalid');
                    userData[$(el).attr('name')] = $(el).val();
                }
            });
            if(this.$('input.invalid').length < 1) {
                var newUser = new UserModel();
                var that = this;
                newUser.save(userData,{
                    error: function(model, response) {
                        response = JSON.parse(response.response);
                        if(typeof response['message'] != 'undefined') alert(response['message']);
                        that.$('input.username,input.email').addClass('invalid');
                        that.$('button.signup').removeClass('hide');
                    },
                    success: function(model, response) {
                        that.$('button.signup').removeClass('hide');
                        Ham.session.authenticate(userData['username'],userData['password']);
                    }
                });
            }
        }
    });

    return SignupView;
});
