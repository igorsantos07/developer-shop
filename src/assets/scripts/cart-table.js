var React = require('react');
var ReactDOM = require('react-dom');

var CartTable = React.createClass({
    render: function() {
        var lines;
        if (this.props.products.length > 0) {
            lines = this.props.products.map(function (prod) {
                return (
                    <CartTable.ProductLine price={prod.price} key={prod.id}>
                        {prod.name}
                    </CartTable.ProductLine>
                );
            });
        } else {
            lines = <tr><td colSpan="3">Your cart is empty :(</td></tr>;
        }

        return (
            <table className="table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Price</th>
                    <th/>
                </tr>
                </thead>

                <tbody>
                {lines}
                </tbody>
            </table>
        );
    }
});

CartTable.ProductLine = React.createClass({
    //TODO: price.toString() does not account for precise decimal places
    render: function () {
        return (
            <tr className="product">
                <td>{this.props.children}</td>
                <td>${this.props.price.toString()}</td>
                <td>
                    <button className="btn btn-danger pull-right">
                        <i className="glyphicon glyphicon-trash" />&nbsp;
                        Remove
                    </button>
                </td>
            </tr>
        );
    }
});

module.exports = CartTable;