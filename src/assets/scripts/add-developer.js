var React = require('react');
var API = require('./api');
var alertify = require('./lib/alertify');

var Form = React.createClass({
    getInitialState: ()=> ({
        username: '',
        price: ''
    }),

    handleUsernameChange: function(e) {
        this.setState({
            username: e.target.value,
            price: ''
        });
    },
    findUserRate: function(e) {
        //FIXME: for some odd reason, the username disappears in the state inside the promise solutions, so we're hard-setting it here, again
        var username = this.state.username;
        var panel = $(e.target).parents('.panel-body');
        panel.addClass('loading-rates');
        API.get('dev/'+this.state.username.trim())
            .success(data => {
                this.state.price = data.rate;
                console.log(data.rateDetails);
            })
            .fail(xhr => {
                console.log(xhr.responseJSON);
                alertify.error('This developer does not have a GitHub account; he\'s too newbie to be hired.');
            })
            .always(()=> {
                panel.removeClass('loading-rates');
                this.state.username = username;
                this.setState(this.state);
            });
    },

    handleSubmit: function(e) {
        e.preventDefault();

        if (!this.state.price) {
            alertify.error("Only acredited GitHub developers can be hired.<br/>Try 'Find rates' after typing a username before adding the developer.");
            return;
        }

        //we cannot mutate state values in the handle functions directly as they would affect the input UX
        var username = this.state.username.trim();
        var price    = parseFloat(this.state.price) || '';

        var $usernameParent = $('#username').parent('.form-group');
        if (!username) {
            $usernameParent.addClass('has-error');
            alertify.log('You need to fill at least the developer username');
            return false;
        } else {
            $usernameParent.removeClass('has-error');
        }

        var prev_state = this.state;
        this.setState(this.getInitialState());
        this.props.onSubmit(username, price)
            .fail(()=> this.setState(prev_state));
    },

    render: function() {
        return (
            <form className="form" onSubmit={this.handleSubmit}>
                <div className="form-group">
                    <label className="control-label" htmlFor="username">GitHub username<sup>*</sup>:</label>
                    <div className="input-group">
                        <input type="text" id="username" className="form-control"
                               value={this.state.username} onChange={this.handleUsernameChange}/>
                        <div className="input-group-addon">
                            <button type="button" className="btn btn-info" onClick={this.findUserRate}>
                                <i className="glyphicon glyphicon-usd"/> Find rates
                            </button>
                        </div>
                    </div>
                </div>

                <div className="form-group">
                    <label className="control-label" htmlFor="price">Price:</label>
                    <div className="input-group">
                        <div className="input-group-addon"><i className="glyphicon glyphicon-usd"/></div>
                        <input type="number" step="0.01" min="0" id="price" className="form-control"
                               readOnly value={this.state.price}/>
                    </div>
                </div>

                <button type="submit" className="btn btn-success">
                    <i className="glyphicon glyphicon-shopping-cart"/> Add to cart
                </button>
            </form>
        );
    }
});

module.exports = Form;