BX(() => {
	BX.bind(BX("click-btn"), "click", function() {
        BX.toggleClass(BX("click-form"), ["view-n", "view-y"]);
    });

	BX.bind(BX("click-buy"), "click", function(e) {
		e.preventDefault();

		let prodId = BX("click-data");
		let prodCount = document.getElementsByClassName("product-item-amount-field")[0];
		let userPhone = BX("click-phone");		
		let mode = BX("click-data").getAttribute("data-basket");
		
        BX.ajax.runComponentAction("dvi:click", "main", {
			mode: "class",
			data: {
				"prodId" : (prodId) ? prodId.getAttribute("data-id") : "",
				"count" : (prodCount) ? prodCount.value : "",
				"phone" : (userPhone) ? BX("click-phone").value : "",
				"type" : mode
			}	
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