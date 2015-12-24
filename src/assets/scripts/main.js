var React = require('react');
var ReactDOM = require('react-dom');
var utils = require('./utils');

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

    checkout: function() {
        API.patch('cart')
            .success(data => {
                $('tr.product').fadeOut('slow', 'swing', ()=> {
                    this.state.products = [];
                    this.setState(this.state);
                });

                alertify
                    .theme('bootstrap')
                    .okBtn('Yey!')
                    .alert('<h1>Order closed!</h1>'+
                        '<p>You\'ll soon receive an email with the next steps.</p>'+
                        '<dl>' +
                            '<dt>Order number</dt>: <dd>#'+data.id+'</dd><br/>' +
                            '<dt>Order total</dt>:  <dd>'+utils.priceFormat(data.total)+'</dd>' +
                        '</dl>');
                console.log(data);
            })
            .fail(()=> {
                alertify
                    .theme('bootstrap')
                    .okBtn('Okay...')
                    .alert('Whoops... We had some trouble finishing your order. Would you try again later, please?');
            })
    },

    render: function() {
        return (<div className="row">
            <div className="col-sm-5">
                <BS.Panel title="Add a developer">
                    <Form onSubmit={this.addDeveloper}/>
                </BS.Panel>
            </div>

            <div className="col-sm-7" id="cartbox">
                <div className="panel-title panel-heading" title="Cart"> {/* weird classes just to get a gray box */}

                    <h2>Cart</h2>
                    <CartTable products={this.state.products} onRemove={this.removeDeveloper} onCheckout={this.checkout}/>
                </div>
            </div>
        </div>);
    }
});

ReactDOM.render(<CartBlock/>, document.getElementById('cart-container'));