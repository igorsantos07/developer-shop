var React = require('react');
var API = require('./api');
var utils = require('./lib/utils');
var alertify = require('./lib/alertify');

var Form = React.createClass({
    getInitialState: ()=> ({
        username: null,
        rate: null,
        hours: null,
        price: null
    }),

    handleUsernameChange: function(e) {
        this.setState({ //explicitly resetting all other developer-related values, as rate must be recalculated
            username: e.target.value,
            rate: null,
            price: null
        });
    },

    handleHoursChange: function(e) {
        this.state.hours = e.target.value;
        this.setState(this.state);
        this.calculatePrice();
    },

    calculatePrice: function() {
        if (this.state.rate && this.state.hours) {
            this.state.price = this.state.rate * this.state.hours;
            this.setState(this.state);
        }
    },

    findUserRate: function(e) {
        //FIXME: for some odd reason, the username disappears in the state inside the promise solutions, so we're hard-setting it later on always(), again
        var username = this.state.username;
        var panel = $(e.target).parents('.panel-body');
        panel.addClass('loading-rates');
        API.get('dev/'+this.state.username.trim())
            .success(data => {
                this.state.rate = data.rate;
                console.log(data.rateDetails);
                this.calculatePrice();
            })
            .fail(xhr => {
                console.log(xhr.responseJSON);
                alertify.error('This developer does not have a GitHub account; he\'s too newbie to be hired.');
            })
            .always(()=> {
                panel.removeClass('loading-rates');
                this.state.username = username; //see fix-me note above
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
                        <input type="text" id="username" className="form-control" required
                               value={this.state.username} onChange={this.handleUsernameChange}/>
                        <div className="input-group-addon">
                            <button type="button" className="btn btn-info" onClick={this.findUserRate}>
                                <i className="glyphicon glyphicon-usd"/> Find rates
                            </button>
                        </div>
                    </div>
                </div>

                <div className="form-group">
                    <label className="control-label" htmlFor="hours">Hire for<sup>*</sup>:</label>
                    <div className="input-group">
                        <input type="number" min="1" step="0.5" id="hours" className="form-control" required
                               value={this.state.hours} onChange={this.handleHoursChange}/>
                        <div className="input-group-addon">hours</div>
                    </div>
                    <p className="help-block">Minimum: 1 hour; fractioned to half-hour</p>
                </div>

                <table className="table">
                    <thead>
                        <tr>
                            <th>Hourly rate</th>
                            <th>Total price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{utils.priceFormat(this.state.rate)}</td>
                            <td>{utils.priceFormat(this.state.price)}</td>
                        </tr>
                    </tbody>
                </table>

                <button type="submit" className="btn btn-success">
                    <i className="glyphicon glyphicon-shopping-cart"/> Add to cart
                </button>
            </form>
        );
    }
});

module.exports = Form;