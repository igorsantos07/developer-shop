var React = require('react');
var ReactDOM = require('react-dom');

var BS = require('./bootstrap/all');

var Form = require('./add-developer');
var CartTable = require('./cart-table');

var devs = [
    //{ id: 1, name: 'brenoc', price: 224 },
    //{ id: 2, name: 'vlribeiro7', price: 123 },
    //{ id: 3, name: 'igorsantos07', price: 777 }
];


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

    render: function() {
        return (<div>
            <div className="row">
                <BS.Panel title="Add a developer">
                    <Form onSubmit={this.addDeveloper}/>
                </BS.Panel>
            </div>

            <div className="cart row">
                <h2>Cart</h2>
                <CartTable products={this.state.products}/>
            </div>

            <div className="totalizer row">
                <div className="col-sm-5">
                    <div className="row">
                        <table className="table">
                            <tbody>
                            <tr className="total">
                                <td>Total</td>
                                <td>$640</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>);
    }
});

ReactDOM.render(<CartBlock/>, document.getElementById('cart-container'));
