module.exports = (function() {
    var prepareUrl = function(uri) {
        return '/api/' + uri;
    };

    return {
        get: function (uri) {
            return $.getJSON(prepareUrl(uri));
        },

        post: function (uri, data) {
            return $.post(prepareUrl(uri), data, null, 'json');
        },

        put: function (uri, data) {
            return $.ajax({ type: "PUT", url: prepareUrl(uri), data: data, dataType: 'json' });
        },

        delete: function (uri, data) {
            return $.ajax({ type: "DELETE", url: prepareUrl(uri), data: data, dataType: 'json' });
        },

        patch: function (uri, data) {
            return $.ajax({ type: "PATCH", url: prepareUrl(uri), data: data, dataType: 'json' });
        }
    };
})();
