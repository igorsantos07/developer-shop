module.exports = {
    priceFormat: function(price, showZero) {
        price = parseFloat(price);
        if (typeof price == 'number') {
            if (!price) {
                price = showZero? '$ 0' : 'free';
            } else {
                price = '$ ' + price.toFixed(2);
            }
        }
        return price;
    }
};