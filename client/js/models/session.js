/*global define*/
define([
    'underscore',
    'backbone',
    'backboneLocalStorage',
    'models/user'
], function (_, Backbone,BackboneLocalStorage,UserModel) {
    'use strict';
//triggers authenticated when session is set and deauthenticated when the session is no longer valid
    var SessionModel = Backbone.Model.extend({
        parse: function(resp) {
            if(typeof resp.data != 'undefined') resp = resp.data;
            var newUser = new UserModel();
            newUser.set(newUser.parse(resp.user));
            resp.user = newUser;
            return resp;
        },
        restoreFromLocalStore: function() {
            if(localStorage.hammockSession != undefined) {
                var localStoreAuth = JSON.parse(localStorage.hammockSession);
                this.set(this.parse(localStoreAuth));
                this.trigger('authenticated');
                return true;
            } else {
                this.trigger('deauthenticated');
                return false;
            }
        },
        saveToLocalStore: function() {
            localStorage.hammockSession = JSON.stringify(this.toJSON());
        },
        isAuthenticated: function() {
            if(this.get('ticket') != undefined) return true;
            else return false;
        },
        authenticate: function(login,pass) {
            var that = this;
            $.getJSON('/session/create/' + btoa(login) + '/' +btoa(pass) , function(request){
                if(request.success) {
                    that.set(that.parse(request));
                    that.saveToLocalStore();
                    that.trigger('authenticated');
                } else return false;
            })

            return false;
        },
        isValid: function() {
            var returnVar = false;
            if(this.isAuthenticated()) {
                $.getJSON('/session/validate/' + this.get('user').get('id') + '/' + this.get('ticket') , function(request){
                    returnVar = request['success'];
                });
            }

            return returnVar;
        }
    });

    return SessionModel;
});
