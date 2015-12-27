var React = require('react');

module.exports = React.createClass({
    render: function() {
        return (
            <div className="panel panel-default">
                <div className="panel-title">
                    <h2 className="panel-heading">{this.props.title}</h2>
                </div>

                <div className="panel-body">
                    {this.props.children}
                </div>
            </div>
        );
    }
});