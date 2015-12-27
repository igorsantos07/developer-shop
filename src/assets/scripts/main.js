var React = require('react');
var ReactDOM = require('react-dom');

var utils = require('./lib/utils');
var BS = require('./lib/bootstrap/all');
var alertify = require('./lib/alertify');

var API = require('./api');
var Form = require('./add-developer');
var CartTable = require('./cart-table');


var CartBlock = React.createClass({
    getInitialState: ()=> ({
        products: null,
        coupon: null
    }),

    componentDidMount: function() {
        API.get('cart')
            .then(data => {
                this.state.products = data.items;
                this.state.coupon   = data.coupon;
                this.setState(this.state);
            });
    },

    addItem: function(item, price, qty) {
        var product = {
            item: item,
            price: price,
            qty: qty
        };

        var prev_products = this.state.products;
        this.state.products = this.state.products.concat([$.extend({
            id: Date.now(),
            final_price: product.price * product.qty
        }, product)]);
        this.setState(this.state);

        return API.put('cart', product)
            .success(data => {
                var idx = this.state.products.length - 1;
                this.state.products[idx].id = data.id;
                this.state.products[idx].final_price = data.final_price;
                this.setState(this.state);
            })
            .fail(()=> {
                this.state.products = prev_products;
                this.setState(this.state);
                alertify.error('Whoops... We had some issues trying to add that item :(');
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

    submitCoupon: function(code) {
        var setCoupon = (coupon)=> {
            this.state.coupon = coupon;
            this.setState(this.state);
            this.componentDidMount(); //refreshes data, so we do not have to handle product discounts here as well
        };

        if (code) {
            return API.post('cart/coupon/', { code: code })
                .fail(()=> alertify.log('Invalid or expired coupon'))
                .success(data => setCoupon({ code: data.code, discount: data.discount }));
        } else {
            return API.delete('cart/coupon')
                .success(()=> setCoupon(null))
        }
    },


    checkout: function() {
        API.patch('cart')
            .success(data => {
                $('tr.product').fadeOut('slow', 'swing', ()=> {
                    this.state.products = [];
                    this.setState(this.state);
                });

                alertify
                    .okBtn('Yey!')
                    .alert('<h1>Order closed!</h1>'+
                        '<p>You\'ll soon receive an email with the next steps.</p>'+
                        '<dl>' +
                            '<dt>Order number</dt>: <dd>#'+data.id+'</dd><br/>' +
                            '<dt>Order total</dt>:  <dd>'+utils.priceFormat(data.total)+'</dd>' +
                        '</dl>');
                console.log(data);
            })
            .fail(xhr => {
                var msg;
                switch (xhr.status) {
                    case 406:
                        msg = 'Hey, it seems your cart is empty!';
                    break;

                    default:
                        msg = 'Whoops... We had some trouble finishing your order. Would you try again later, please?';
                    break;
                }

                alertify.okBtn('Okay...').alert(msg);
            })
    },

    render: function() {
        return (<div className="row">
            <div className="col-sm-5">
                <BS.Panel title="Add a developer">
                    <Form onSubmit={this.addItem}/>
                </BS.Panel>
            </div>

            <div className="col-sm-7" id="cartbox">
                <div className="panel-title panel-heading" title="Cart"> {/* weird classes just to get a gray box */}

                    <h2>Cart</h2>
                    <CartTable products={this.state.products} appliedCoupon={this.state.coupon}
                               onRemove={this.removeDeveloper} onCheckout={this.checkout}
                               onCoupon={this.submitCoupon}/>
                </div>
            </div>
        </div>);
    }
});

ReactDOM.render(<CartBlock/>, document.getElementById('cart-container'));