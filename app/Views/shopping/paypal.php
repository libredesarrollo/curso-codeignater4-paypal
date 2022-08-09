<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal</title>

    <script src="https://www.paypal.com/sdk/js?client-id=AUu2CpCBrva4gbNjqB5IvdV16V93hPLgumgyL7iJgQPs64Rdrp35EepAGDE8GtDlZIwg7i44FrGDY8Eq"></script>

</head>

<body>
    <h1>Pagos con Paypal</h1>

    <div id="paypalCard"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                // Set up the transaction
                return actions.order.create({
                    purchase_units: [{
                        description: "Super Product",
                        amount: {
                            value: '0.01'
                        }
                    }],
                    application_context: {
                        shipping_preference: "NO_SHIPPING"
                    }
                });
            },
            onApprove: function(data, actions) {
                console.log(data)
                fetch('/paypal/process/' + data.orderID, {
                        method: 'POST',
                    })
                    .then(res => res.json())
                    .then(res => {
                        console.log(res)
                        alert(res.msj)
                    })
            }
        }).render('#paypalCard');
    </script>

</body>

</html>