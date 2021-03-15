BX(() => {
	BX.bind(BX("click-btn"), "click", function() {
        BX.toggleClass(BX("click-form"), ["view-n", "view-y"]);
    });

	BX.bind(BX("click-buy"), "click", function(e) {
		e.preventDefault();

		let mode = BX("click-data").getAttribute("data-basket");
		let nameAction = "";
		let userData = {};
		let userPhone = BX("click-phone").value;

		if (mode !== "Y") {
			nameAction = "product";
			userData = {
				"phone" : userPhone,
				"id" : BX("click-data").getAttribute("data-id"),
				"count" : document.getElementsByClassName("product-item-amount-field")[0].value
			};
		} else {
			nameAction = "basket";
			userData = {
				"phone" : userPhone
			};
		}

        BX.ajax.runComponentAction("dvi:click", nameAction, {
			mode: "class",
			data: userData	
		}).then(function (response) { // success
			console.log(1);
			console.log(response);
			BX("click-one").hidden = true;
			BX("answer").textContent = "Заказ оформлен!";		
		}, function (response) { // error
			console.log(2);
			console.log(response);
			BX("click-one").hidden = true;
			BX("answer").textContent = "Заказ не оформлен!";
			//BX("answer").textContent = response.errors[0].message;
		});
    });
});