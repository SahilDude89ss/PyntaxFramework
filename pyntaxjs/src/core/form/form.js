//(function(app) {
//    app.registerModule('forms', _.extend({
//        init: function() {
//            var $this = this;
//            $('input[data-type="date"]').pickadate();
//        }
//    }));
//}(Pyntax.App));

$(document).ready(function(){
    $('input[data-type="date"]').pickadate();
});