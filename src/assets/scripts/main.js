var React = require('react');
var ReactDOM = require('react-dom');

var BS = require('./bootstrap/all');
var alertify = require('../../../node_modules/alertify.js/dist/js/alertify');

var API = require('./api');
var Form = require('./add-developer');
var CartTable = require('./cart-table');


var CartBlock = React.createClass({
    getInitialState: ()=> ({ products: [] }),

    addDeveloper: function(username, price) {
        var product = {
            item: username,
            price: price || 0
        };

        var prev_products = this.state.products;
        this.state.products = this.state.products.concat([$.extend({ id: Date.now() }, product)]);
        this.setState(this.state);

        return API.put('casrt', product)
            .success((data)=> {
                this.state.products[this.state.products.length - 1].id = data.id;
                this.setState(this.state);
            })
            .fail(()=> {
                this.state.products = prev_products;
                this.setState(this.state);
                alertify.logPosition('top right').error('Ops... Houve um problema ao adicionar o item ao carrinho :(');
            });
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