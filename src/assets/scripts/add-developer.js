var React = require('react');

var Form = React.createClass({
    getInitialState: ()=> ({
        username: '',
        price: ''
    }),

    handleUsernameChange: function(e) { this.setState({ username: e.target.value }) },
    handlePriceChange: function(e) { this.setState({ price: e.target.value }) },
    handleSubmit: function(e) {
        e.preventDefault();

        //we cannot mutate the state values in the handle functions directly as they would affect the input UX
        var username = this.state.username.trim();
        var price    = parseFloat(this.state.price) || '';

        var $usernameParent = $('#username').parent('.form-group');
        if (!username) {
            $usernameParent.addClass('has-error');
            return false;
        } else {
            $usernameParent.removeClass('has-error');
        }

        this.props.onSubmit(username, price);
        this.setState(this.getInitialState());
    },

    render: function() {
        return (
            <form className="form" onSubmit={this.handleSubmit}>

                <div className="form-group">
                    <label className="control-label" htmlFor="username">GitHub username<sup>*</sup>:</label>
                    <input type="text" id="username" className="form-control"
                           value={this.state.username} onChange={this.handleUsernameChange}/>
                </div>

                <div className="form-group">
                    <label className="control-label" htmlFor="price">Price:</label>
                    <input type="text" id="price" className="form-control"
                           value={this.state.price} onChange={this.handlePriceChange}/>
                </div>

                <button type="submit" className="btn btn-success">
                    <i className="glyphicon glyphicon-shopping-cart"/> Add
                </button>
            </form>
        );
    }
});

module.exports = Form;