var React = require('react');
var utils = require('./lib/utils');
var alertify = require('./lib/alertify');

/**
 * @property {Array} products
 * @property {Object} appliedCoupon
 * @property {function} onRemove
 * @property {function} onCheckout
 * @property {function} onCoupon
 */
var CartTable = React.createClass({
    getInitialState: ()=> ({ coupon: null }),

    couponChange: function(e) {
        this.state.coupon = e.target.value;
        this.setState(this.state);
    },

    onCouponSubmit: function(e) {
        e.preventDefault();

        if (!this.state.coupon) {
            alertify.error('Please, fill in the coupon code first');
            return;
        }

        this.props.onCoupon(this.state.coupon)
            .success(()=> {
                this.state.coupon = '';
                this.setState(this.state);
            })
    },

    onCouponRemoval: function(e) {
        this.props.onCoupon(null);
    },

    render: function() {
        var lines, total;
        if (this.props.products) {
            total = this.props.products.reduce((total, prod)=> total + parseFloat(prod.final_price), 0);

            if (this.props.products.length > 0) {
                lines = this.props.products.map((prod)=> {
                    //FIXME: is there a way to avoid passing a passed prop down? (onRemove)
                    return (
                        <CartTable.ProductLine {...prod} key={prod.id} onRemove={this.props.onRemove}>
                            {prod.item}
                        </CartTable.ProductLine>
                    );
                });
            } else {
                lines = <tr><td colSpan="3">Your cart is empty :(</td></tr>;
            }
        } else {
            total = 0;
            lines = <tr><td colSpan="3">Loading...</td></tr>;
        }

        return (
            <table className="table" id="cartTable">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th/>
                    </tr>
                </thead>

                <tbody>{lines}</tbody>

                <tfoot>
                    <tr>
                        <th title="Tip: SHIPIT">Got a coupon?</th>
                        <td colSpan="2">
                            <form onSubmit={this.onCouponSubmit}>
                                <input className="form-control" value={this.state.coupon} onChange={this.couponChange}/>
                            </form>
                        </td>
                        <td colSpan="2">
                            <button className="btn btn-info" onClick={this.onCouponSubmit}>
                                <i className="glyphicon glyphicon-usd"/> Calculate
                            </button>
                        </td>
                    </tr>

                    {(()=> {
                        if (this.props.appliedCoupon) {
                            return (<tr>
                                <th>Applied coupon</th>
                                <td colSpan="2">
                                    {this.props.appliedCoupon.code}:&nbsp;{this.props.appliedCoupon.discount * 100}%
                                </td>
                                <th colSpan="2">
                                    <button className="btn btn-warning" onClick={this.onCouponRemoval}>
                                        <i className="glyphicon glyphicon-trash"/> Remove
                                    </button>
                                </th>
                            </tr>);
                        }
                    })()}

                    <tr>
                        <th>Total</th>
                        <th colSpan="2">{utils.priceFormat(total, true)}</th>
                        <th colSpan="2">
                            <button className="btn btn-success" onClick={this.props.onCheckout}>
                                <i className="glyphicon glyphicon-credit-card"/>
                                Checkout
                                <i className="glyphicon glyphicon-menu-right"/>
                            </button>
                        </th>
                    </tr>
                </tfoot>
            </table>
        );
    }
});


/**
 * @property {number} id
 * @property {function} children
 * @property {number} price
 * @property {function} onRemove
 */
CartTable.ProductLine = React.createClass({
    onRemove: function() {
        return this.props.onRemove(this.props.id);
    },

    render: function () {
        return (
            <tr className="product">
                <td>{this.props.children}</td>
                <td>{utils.priceFormat(this.props.price)}</td>
                <td>{this.props.qty}</td>
                <td>{utils.priceFormat(this.props.final_price)}</td>
                <td>
                    <button className="btn btn-warning" onClick={this.onRemove}>
                        <i className="glyphicon glyphicon-trash" />
                    </button>

                </td>
            </tr>
        );
    }
});

module.exports = CartTable;