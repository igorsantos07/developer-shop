var React = require('react');
var ReactDOM = require('react-dom');

var BS = require('./bootstrap/all');

var Form = require('./add-developer');
var CartTable = require('./cart-table');


var CartBlock = React.createClass({
    getInitialState: ()=> ({ products: [] }),

    addDeveloper: function(username, price) {
        this.state.products.push({
            id: Date.now(),
            name: username,
            price: price || 0
        });
        this.setState(this.state);
    },

    removeDeveloper: function(id) {
        this.state.products = this.state.products.filter(prod => prod.id != id);
        this.setState(this.state);
    },

    render: function() {
        return (<div className="row">
            <div className="col-sm-5">
                <BS.Panel title="Add a developer">
                    <Form onSubmit={this.addDeveloper}/>
                </BS.Panel>
            </div>

            <div className="col-sm-7">
                <h2>Cart</h2>
                <CartTable products={this.state.products} onRemove={this.removeDeveloper}/>
            </div>
        </div>);
    }
});

ReactDOM.render(<CartBlock/>, document.getElementById('cart-container'));