var React = require('react');
var ReactDOM = require('react-dom');

var BS = require('./bootstrap/all');
var alertify = require('../../../node_modules/alertify.js/dist/js/alertify');

var API = require('./api');
var Form = require('./add-developer');
var CartTable = require('./cart-table');


var CartBlock = React.createClass({
    getInitialState: ()=> (
        { products: null }
    ),

    componentDidMount: function() {
        API.get('cart')
            .then(data => {
                this.state.products = data.items;
                this.setState(this.state);
            });
    },

    addDeveloper: function(username, price) {
        var product = {
            item: username,
            price: price || 0
        };

        var prev_products = this.state.products;
        this.state.products = this.state.products.concat([$.extend({ id: Date.now() }, product)]);
        this.setState(this.state);

        return API.put('cart', product)
            .success(data => {
                this.state.products[this.state.products.length - 1].id = data.id;
                this.setState(this.state);
            })
            .fail(()=> {
                this.state.products = prev_products;
                this.setState(this.state);
                alertify.logPosition('top right').error('Whoops... We had some issues trying to add that item :(');
            });
    },

    removeDeveloper: function(id) {
        var prev_products = this.state.products;
        this.state.products = this.state.products.filter(prod => prod.id != id);
        this.setState(this.state);

        API.delete('cart/item/'+id)
            .fail(() => {
                this.state.products = prev_products;
                this.setState(this.state);
            });
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