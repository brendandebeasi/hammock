/*global define*/
define([
    'underscore',
    'backbone',
], function (_, Backbone) {
    'use strict';
    var UserModel = Backbone.Model.extend({
        initialize: function() {
        },
        url : function() {
            return this.id ? '/user/' + this.id : '/user';
        },
        parse: function(resp) {
            if(typeof resp.data != 'undefined') resp = resp.data;
            if(typeof resp['_id']['$id'] != 'undefined') resp.id = resp['_id']['$id'];
            return resp;
        }
    });

    return UserModel;
});
