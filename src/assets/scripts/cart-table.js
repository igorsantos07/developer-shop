var React = require('react');
var utils = require('./utils');

/**
 * @property {Array} products
 * @property {function} onRemove
 * @property {function} onCheckout
 */
var CartTable = React.createClass({
    render: function() {
        var lines, total;
        if (this.props.products) {
            total = this.props.products.reduce((total, prod)=> { return total + prod.price }, 0);

            if (this.props.products.length > 0) {
                lines = this.props.products.map((prod)=> {
                    //FIXME: is there a way to avoid passing a passed prop down? (onRemove)
                    return (
                        <CartTable.ProductLine price={prod.price} key={prod.id} id={prod.id} onRemove={this.props.onRemove}>
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
                    <th/>
                </tr>
                </thead>

                <tbody>{lines}</tbody>

                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>{utils.priceFormat(total, true)}</th>
                        <th>
                            <button className="btn btn-primary pull-right" onClick={this.props.onCheckout}>
                                <i className="glyphicon glyphicon-credit-card"/>
                                &nbsp;Checkout
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
                <td>
                    <button className="btn btn-warning pull-right" onClick={this.onRemove}>
                        <i className="glyphicon glyphicon-trash" />
                    </button>

                </td>
            </tr>
        );
    }
});

module.exports = CartTable;