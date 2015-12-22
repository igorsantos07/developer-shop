var React = require('react');
var utils = require('./utils');

/**
 * @property {Array} products
 * @property {function} onRemove
 */
var CartTable = React.createClass({
    render: function() {
        var lines;
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

        var total = this.props.products.reduce((total, prod)=> { return total + prod.price }, 0);

        return (
            <table className="table">
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
                        <th/>
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
                    <button className="btn btn-danger pull-right" onClick={this.onRemove}>
                        <i className="glyphicon glyphicon-trash" />&nbsp;
                        Remove
                    </button>
                </td>
            </tr>
        );
    }
});

module.exports = CartTable;