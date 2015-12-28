var React = require('react');
var API = require('./api');
var utils = require('./lib/utils');
var alertify = require('./lib/alertify');
var DevSelect = require('./dev-select');

var Form = React.createClass({
    getInitialState: ()=> ({
        username: null,
        rate: null,
        hours: null,
        price: null,
        org: null,
        devs: []
    }),

    handleOrgChange: function(e) {
        this.setState({
            org: e.target.value,
            username: null,
            devs: []
        });
    },

    handleUsernameChange: function(e) {
        //explicitly resetting all other developer-related values, as rate must be recalculated
        this.state.username = e.target.value;
        this.state.rate = null;
        this.state.price = null;
        this.setState(this.state);
    },

    handleUsernameSelect: function(user) {
        this.handleUsernameChange({ target: { value: user.username }});
        this.findUserRate();
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

    setLoadingPanel: function(className) {
        document.querySelector('#add-developer .panel-body').className = 'panel-body '+(className || '');
    },

    findDevelopers: function() {
        this.setLoadingPanel('loading');

        var uri = 'dev/organization/'+this.state.org+'?level=';
        API.get(uri + 'basic')
            //.success(()=> API.get(uri + 'complete')) //preloading developer rates in the server memory
            .success(data => {
                console.log('Total of '+data.size+' devs were found on '+this.state.org);
                this.setState({ devs: data.members });
            })
            .success(()=> {
                API.get(uri + 'user').success(data => this.setState({ devs: data.members }));
            })
            .fail((xhr)=> {
                if (xhr.status == 404) {
                    alertify.error('Organization not found');
                } else {
                    alertify.error('We had some issues when retrieving the organization members... Would you try again later?');
                }
            })
            .always(()=> this.setLoadingPanel());

    },

    findUserRate: function() {
        //FIXME: for some odd reason, the username disappears in the state inside the promise solutions, so we're hard-setting it later on always(), again
        var username = this.state.username;
        this.setLoadingPanel('loading-rates');
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
                this.setLoadingPanel();
                this.state.username = username; //see fix-me note above
                this.setState(this.state);
            });
    },

    handleSubmit: function(e) {
        e.preventDefault();

        if (!this.state.price) {
            alertify.error("Only acredited GitHub developers can be hired.<br/>Try hitting 'Find rates' after typing a username before adding the developer, or type an organization and then, select a user.");
            return;
        }

        //we cannot mutate state values in the handle functions directly as they would affect the input UX
        var username = this.state.org.trim()+'/'+this.state.username.trim();
        var rate     = parseFloat(this.state.rate);
        var hours    = parseFloat(this.state.hours);

        var prev_state = this.state;
        this.setState($.extend(this.getInitialState(), {
            org: this.state.org,
            devs: this.state.devs
        }));
        this.props.onSubmit(username, rate, hours)
            .fail(()=> this.setState(prev_state));
    },

    render: function() {
        return (
            <form className="form" onSubmit={this.handleSubmit}>
                <div className="form-group">
                    <label className="control-label" htmlFor="org">GitHub organization:</label>
                    <div className="input-group">
                        <input type="text" id="org" className="form-control" list="orgs"
                               value={this.state.org} onChange={this.handleOrgChange}/>
                        <div className="input-group-addon">
                            <button type="button" className="btn btn-info"
                                    disabled={!this.state.org} onClick={this.findDevelopers}>
                                <i className="glyphicon glyphicon-list"/> Load devs
                            </button>
                        </div>
                    </div>

                    <datalist id="orgs">
                        <option value="vtex"/>
                        <option value="PHPRio"/>
                        <option value="laravel"/>
                        <option value="laravel-ardent"/>
                        <option value="yiisoft"/>
                        <option value="Luracast"/>
                        <option value="HotelUrbano"/>
                        <option value="php"/>
                    </datalist>
                </div>

                <div className="form-group">
                    <label className="control-label" htmlFor="username">GitHub username<sup>*</sup>:</label>
                        {(()=> (this.state.devs.length)?
                            (
                                <DevSelect id="username" options={this.state.devs} searchable={false}
                                           onChange={this.handleUsernameSelect} value={this.state.username}/>
                            ) : (
                                <div className="input-group">
                                    <input type="text" id="username" className="form-control" required
                                           value={this.state.username} onChange={this.handleUsernameChange}/>
                                    <div className="input-group-addon">
                                        <button type="button" className="btn btn-info"
                                            disabled={!this.state.username} onClick={this.findUserRate}>
                                            <i className="glyphicon glyphicon-usd"/> Find rates
                                        </button>
                                    </div>
                                </div>
                            )
                        )()}
                </div>

                <div className="form-group">
                    <label className="control-label" htmlFor="hours">Hire for<sup>*</sup>:</label>
                    <div className="input-group">
                        <input type="number" min="1" max="999.5" step="0.5" id="hours" className="form-control" required
                               value={this.state.hours} onChange={this.handleHoursChange}/>
                        <div className="input-group-addon">hour(s)</div>
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