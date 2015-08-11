var Pyntax = Pyntax || {};

Pyntax.App = (function(){
    var _app, _modules = {};

    /**
     * This function converts an HTML element to a jQuery element.
     *
     * @param selector
     * @returns {*}
     */
    var get$Object = function(selector) {
        return select instanceof $ ? selector : $(selector);
    };

    function App(config) {
        config = config || {};

        return _.extend({

            $containerEl : get$Object(config.el || "div.content-wrapper"),

            $contentEl : get$Object(config.container || "div.content"),

        }, this, Backbone.Events);
    }

    return {
        init: function(opts) {
            _app = _app || _.extend(this, new App(opts));
        }
    }

})();