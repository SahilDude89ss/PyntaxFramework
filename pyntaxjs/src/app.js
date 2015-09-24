//var Pyntax = Pyntax || {};
//
//Pyntax.App = (function(){
//    var _app, _modules = {};
//
//    /**
//     * This function converts an HTML element to a jQuery element.
//     *
//     * @param selector
//     * @returns {*}
//     */
//    var get$Object = function(selector) {
//        return select instanceof $ ? selector : $(selector);
//    };
//
//    function App(config) {
//        var appId = _.uniqueId("PyntaxApp_");
//
//        config = config || {};
//
//        return _.extend({
//            appId: appId,
//            api: null
//
//            //$containerEl : get$Object(config.el || "div.content-wrapper"),
//            //$contentEl : get$Object(config.container || "div.content")
//        }, this, Backbone.Events);
//    }
//
//    return {
//        init: function(opts) {
//            _app = _app || _.extend(this, new App(opts));
//
//            _app.event.register(
//                "app:init",
//                this
//            );
//
//            _app.event.register(
//                "app:apiCall",
//                this
//            );
//
//            _app.event.register(
//                "model:fetchData",
//                this
//            );
//        },
//
//        registerModule: function(moduleName, moduleDefinition) {
//            _modules[moduleName] = moduleDefinition;
//
//            if(_.isFunction(moduleDefinition.init)) {
//                _modules[moduleName].init.call(_app);
//            }
//        }
//    }
//
//})();