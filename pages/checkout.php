<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f4f4f9;
        }
        
        #payment-form {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        #card-element, #upi-id {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            background: #fff;
            width: 100%;
            font-size: 16px;
        }
        
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .button-container button {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
            margin: 0 5px;
        }

        #submit-button {
            background-color: #28a745;
            color: white;
        }
        
        #submit-button:hover {
            background-color: #218838;
        }

        #back-button {
            background-color: #dc3545;
            color: white;
        }

        #back-button:hover {
            background-color: #c82333;
        }
        
        #payment-message {
            margin-top: 15px;
            font-size: 14px;
            color: red;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Secure Checkout</h2>
    <form id="payment-form">
        <label>
            <input type="radio" name="payment-method" value="card" checked> Pay with Card
        </label>
        <label>
            <input type="radio" name="payment-method" value="upi"> Pay with UPI
        </label>

        <div id="card-container">
            <div id="card-element"></div>
        </div>

        <div id="upi-container" class="hidden">
            <input type="text" id="upi-id" placeholder="Enter UPI ID (e.g., abc@upi)">
        </div>

        <div class="button-container">
            <button type="button" id="back-button" onclick="goBack()">Go Back</button>
            <button id="submit-button">Pay Securely</button>
        </div>

        <div id="payment-message" class="hidden"></div>
    </form>

    <script>
        const stripe = Stripe("YOUR_STRIPE_PUBLIC_KEY");
        const elements = stripe.elements();
        const card = elements.create("card");
        card.mount("#card-element");

        // Handle Payment Method Selection
        document.querySelectorAll('input[name="payment-method"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === "upi") {
                    document.getElementById("upi-container").classList.remove("hidden");
                    document.getElementById("card-container").classList.add("hidden");
                } else {
                    document.getElementById("card-container").classList.remove("hidden");
                    document.getElementById("upi-container").classList.add("hidden");
                }
            });
        });

        document.getElementById("payment-form").addEventListener("submit", async (event) => {
            event.preventDefault();
            const paymentMethodType = document.querySelector('input[name="payment-method"]:checked').value;

            let paymentData;
            if (paymentMethodType === "card") {
                const { paymentMethod, error } = await stripe.createPaymentMethod({ type: "card", card: card });
                if (error) {
                    document.getElementById("payment-message").textContent = error.message;
                    return;
                }
                paymentData = { payment_method: paymentMethod.id };
            } else {
                const upiId = document.getElementById("upi-id").value;
                if (!upiId.includes("@")) {
                    document.getElementById("payment-message").textContent = "Please enter a valid UPI ID.";
                    document.getElementById("payment-message").style.color = "red";
                    return;
                }
                paymentData = { upi_id: upiId };
            }

            fetch("process_payment.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("payment-message").textContent = data.message;
                document.getElementById("payment-message").style.color = "green";
            })
            .catch(error => {
                document.getElementById("payment-message").textContent = "Payment failed. Try again.";
                document.getElementById("payment-message").style.color = "red";
            });
        });

        // Go back function
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
