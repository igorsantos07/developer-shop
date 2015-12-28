var React = require('react');
var Select = require('react-select');
var select2css = document.createElement('link');
select2css.rel = 'stylesheet';
select2css.href = '/lib/react-select.css';
document.querySelector('head').appendChild(select2css);

var DevSelect = React.createClass({
    getInitialState: ()=> ({ }),

    onChange(value) {
        this.setState({ value });
        if (this.props.onChange) {
            this.props.onChange(value);
        }
    },

    valueRenderer(dev) {
        if (!this.props.value) {
            this.setState({ value: null });
        } else {
            return <DevSelect.Value dev={dev} details={false} />;
        }
    },

    optionRenderer(dev) {
        return <DevSelect.Value dev={dev} details={true}/>;
    },

    render() {
        return <Select {...this.props}
                valueRenderer={this.valueRenderer}
                optionRenderer={this.optionRenderer}
                value={this.state.value}
                onChange={this.onChange}/>
    }
});

DevSelect.Value = React.createClass({
    render() {
        var ellipsis = '', repos = '', followers = '', details = '';
        var dev = this.props.dev;

        if (this.props.details) {
            if (dev.name) {
                if (dev.repos == 0) {
                    repos = 'No repos';
                } else {
                    repos = dev.repos + (dev.repos > 1 ? ' repos' : ' repo');
                }
                if (dev.followers == 0) {
                    followers = 'No followers';
                } else {
                    followers = dev.followers + (dev.followers > 1 ? ' followers' : ' follower');
                }
                details = repos + ', ' + followers;
            } else {
                ellipsis = '...';
            }
        }

        return (
            <div>
                <img src={dev.avatar} alt={dev.username} className={(this.props.details && dev.name)? '' : 'small'}/>
                <strong>@{dev.username}{ellipsis}</strong> <small>{dev.name}</small>
                {(()=> (details)? <em><br/>{details}</em> : '')()}
            </div>
        );
    }
});

module.exports = DevSelect;