var React = require('react');
var ReactDOM = require('react-dom');

var devs = [
    { id: 1, name: 'brenoc', price: 224 },
    { id: 2, name: 'vlribeiro7', price: 123 },
    { id: 3, name: 'igorsantos07', price: 777 }
];

var ProductLine = React.createClass({
    render: function() {
        //TODO: price.toString() does not account for precise decimal places
        return (
            <tr className="product">
                <td>{this.props.children}</td>
                <td>${this.props.price.toString()}</td>
                <td>
                    <button className="btn btn-danger pull-right">
                        <i className="glyphicon glyphicon-trash"/>&nbsp;
                        Remove
                    </button>
                </td>
            </tr>
        );
    }
});

var CartTable = React.createClass({
    render: function() {
        var lines;
        if (this.props.products.length > 0) {
            lines = this.props.products.map(function (prod) {
                return (
                    <ProductLine price={prod.price} key={prod.id}>
                        {prod.name}
                    </ProductLine>
                );
            });
        } else {
            lines = <tr><td colspan="3">Your cart is empty :(</td></tr>;
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

ReactDOM.render(<CartTable products={devs}/>, document.getElementById('cart-table'));