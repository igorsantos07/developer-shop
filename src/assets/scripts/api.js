module.exports = (function() {
    var prepareUrl = function(uri) {
        return '/api/' + uri;
    };

    return {
        get: function (uri) {
            return $.getJSON(prepareUrl(uri));
        },

        post: function (uri, data) {
            return $.post(prepareUrl(uri), data || "undefined", null, 'json');
        },

        put: function (uri, data) {
            return $.ajax({ type: "PUT", url: prepareUrl(uri), data: data || "undefined", dataType: 'json' });
        },

        delete: function (uri, data) {
            return $.ajax({ type: "DELETE", url: prepareUrl(uri), data: data || "undefined", dataType: 'json' });
        },

        patch: function (uri, data) {
            return $.ajax({ type: "PATCH", url: prepareUrl(uri), data: data || "undefined", dataType: 'json' });
        }
    };
})();
