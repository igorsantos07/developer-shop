module.exports = {
    priceFormat: function(price) {
        price = parseFloat(price);
        if (typeof price == 'number') {
            if (!price) {
                price = 'free';
            } else {
                price = price.toString();
                if (price.indexOf('.') == -1) {
                    price += '.00';
                } else {
                    var parts = price.split('.');
                    var decimals = parts[1].substr(0, 2);
                    if (decimals.length < 2) {
                        decimals += '0';
                    }
                    price = parts[0] + '.' + decimals;
                }

                price = '$ ' + price;
            }
        }
        return price;
    }
};