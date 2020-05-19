window.onhashchange = function() {
    console.log("ERROR MSG");
    var url = window.location.hash;
    url = url.substring(1);
    var split = url.split("?");
    url = split[0];
    var params = split[1];
    requestPage(url,params);
}
window.addEventListener("load",function(){
    var url = window.location.hash;
    url = url.substring(1);
    var split = url.split("?");
    url = split[0];
    var params = split[1];
    if(url == "") {
        window.location.hash = "home";
    } else { 
        requestPage(url,params);
    }
});

var content = document.getElementById('content');
$('.stop').click(function(e){
    e.preventDefault();
    window.location.hash = e.target.id;
});

function requestPage(link, params) {
    var xhttp = new XMLHttpRequest();
    console.log(link);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          content.innerHTML = xhttp.responseText;
        } else if (this.status == 404) {
            window.location.hash = 'home';
        } else {
            content.innerHTML = xhttp.responseText;
        }
      };
    if(params == '')
        xhttp.open("GET", "pages/"+link+".php", true);
    else
        xhttp.open("GET", "pages/"+link+".php?"+params, true);
    xhttp.send();
}

function loadSOrderView() {
    var val = document.getElementById('viewSelect').value;
    if (val == 1) {
	    document.getElementById('cont').className = "container";
        document.getElementById('cont_select').className = "container";
	   
        //document.getElementById('viewDateSelect').style.display = "none"; 

        document.getElementById('unfilled_t').style.display = "";

	    document.getElementById('filled_t').style.display = "none";
	    
        document.getElementById('singleHeader').style.display = "";
	    document.getElementById('doubleHeader').style.display = "none";
		document.getElementById('singleHeaderText').innerHTML = "Un-filled Orders";
    } else if (val == 2) {
        document.getElementById('cont').className = "container";
        document.getElementById('cont_select').className = "container";
	    
        //document.getElementById('viewDateSelect').style.display = ""; 

        document.getElementById('unfilled_t').style.display = "none";
	    
        document.getElementById('filled_t').style.display = "";
	    
        document.getElementById('singleHeader').style.display = "";
	    document.getElementById('doubleHeader').style.display = "none";
		document.getElementById('singleHeaderText').innerHTML = "Filled Orders";
    } else if (val == 3) {
	    document.getElementById('cont').className = "container-order mx-auto";
	    document.getElementById('cont_select').className = "container-order";
	    
        //document.getElementById('viewDateSelect').style.display = ""; 
        
        document.getElementById('unfilled_t').style.display = "";
	    
        document.getElementById('filled_t').style.display = "";
	    
        document.getElementById('singleHeader').style.display = "none";
	    document.getElementById('doubleHeader').style.display = "";
    }
}

function generatePDF(val) {
    var sordID = document.getElementById('s'+val).value;
    var empID = document.getElementById('e'+val).value;
	var URL = "includes/viewSOrder.php?";
	URL += "sID="+sordID;
	URL += "&eID="+empID;
    window.open(URL, "_blank");
}

function fillSOrder(val) {
	var sordID = document.getElementById('sordID'+val).value;
	var empID = document.getElementById('empID'+val).value;
	var time = document.getElementById('time'+val).value;
	var URL = "includes/fillSOrder.php?";
	URL += "sID="+sordID;
	URL += "&eID="+empID;
	URL += "&t="+time;
	window.location.reload();
    window.open(URL, "_blank");

}

function showEmployeeForm() {
	document.getElementById('employeeList').style.display = "none";
	document.getElementById('employeeListHeader').style.display = "none";
	document.getElementById('addEmployeeForm').style.display = "";
	document.getElementById('addEmployeeFormHeader').style.display = "";
}

function showEmployeeList() {
	document.getElementById('employeeList').style.display = "";
	document.getElementById('employeeListHeader').style.display = "";
	document.getElementById('addEmployeeForm').style.display = "none";
	document.getElementById('addEmployeeFormHeader').style.display = "none";
}

function addEmployee() {
	var name = document.getElementById('empName').value;
	var bd = document.getElementById('empBD').value;
	var sex = document.getElementById('empSex').value;
	var ssn = document.getElementById('empSSN').value;
	var addr = document.getElementById('empAddr').value;
	var dept = document.getElementById('empDept').value;
	var wage = document.getElementById('empWage').value;
	var sdate = document.getElementById('empSDate').value;
	if (name != "" && bd != "" && sex != "" && ssn != "" && 
	addr != "" && dept != "" && wage != "" && sdate != "") {
		var URL = "includes/addemployee.php?";
		URL += "name="+name;
		URL += "&bd="+bd;
		URL += "&sex="+sex;
		URL += "&ssn="+ssn;
		URL += "&addr="+addr;
		URL += "&dept="+dept;
		URL += "&wage="+wage;
		URL += "&sdate="+sdate;
		window.location.href = URL;
	}
}

function getPrice(item, size) {
	var price = 0;
	if (item == "Hot Cocoa" && size == "Small") {
		price = 0.30 * 5;
	} else if (item == "Hot Cocoa" && size == "Medium") {
		price = 0.40 * 5;
	} else if (item == "Hot Cocoa" && size == "Large") {
		price = 0.50 * 5;
	} else if (item == "Coffee" && size == "Small") {
		price = 0.40 * 5;
	} else if (item == "Coffee" && size == "Medium") {
		price = 0.50 * 5;
	} else if (item == "Coffee" && size == "Large") {
		price = 0.60 * 5;
	} else if (item == "Tea" && size == "Small") {
		price = 0.40 * 5;
	} else if (item == "Tea" && size == "Medium") {
		price = 0.50 * 5;
	} else if (item == "Tea" && size == "Large") {
		price = 0.60 * 5;
	} else if (item == "Decaf" && size == "Small") {
		price = 0.50 * 5;
	} else if (item == "Decaf" && size == "Medium") {
		price = 0.60 * 5;
	} else if (item == "Decaf" && size == "Large") {
		price = 0.70 * 5;
	} else if (item == "Espresso" && size == "Small") {
		price = 0.60 * 5;
	} else if (item == "Espresso" && size == "Medium") {
		price = 0.75 * 5;
	} else if (item == "Espresso" && size == "Large") {
		price = 0.90 * 5;
	} else if (item == "Americano" && size == "Small") {
		price = 0.45 * 5;
	} else if (item == "Americano" && size == "Medium") {
		price = 0.60 * 5;
	} else if (item == "Americano" && size == "Large") {
		price = 0.75 * 5;
	} else if (item == "French Press" && size == "Small") {
		price = 0.45 * 5;
	} else if (item == "French Press" && size == "Medium") {
		price = 0.60 * 5;
	} else if (item == "French Press" && size == "Large") {
		price = 0.75 * 5;
	} else if (item == "Frappe" && size == "Small") {
		price = 0.50 * 5;
	} else if (item == "Frappe" && size == "Medium") {
		price = 0.75 * 5;
	} else if (item == "Frappe" && size == "Large") {
		price = 1.00 * 5;
	} else if (item == "Cold Brew" && size == "Small") {
		price = 0.45 * 5;
	} else if (item == "Cold Brew" && size == "Medium") {
		price = 0.55 * 5;
	} else if (item == "Cold Brew" && size == "Large") {
		price = 0.65 * 5;
	} else if (item == "Turkish Coffee" && size == "Small") {
		price = 0.50 * 5;
	} else if (item == "Turkish Coffee" && size == "Medium") {
		price = 0.65 * 5;
	} else if (item == "Turkish Coffee" && size == "Large") {
		price = 0.80 * 5;
	} else {
		price = undefined;
	}
	return price;
}

var cart = new Array();
var discount = 0;
var customer = undefined;
var phone = undefined;

function getCartTotal() {
	var total = 0;
	for (var i = 0; i < cart.length; i++) {
		total += cart[i][3]*cart[i][2]*(1-discount/100);
	}
	return total.toFixed(2);
}

function drawCartTable() {
	var HTML = "<table class='table table-condensed table-borderless table-striped table-sm'><thead><tr></tr><thead><tbody>";
	for (var i = 0; i < cart.length; i++) {
		var total = (cart[i][3]*cart[i][2]).toFixed(2);
		HTML += "<tr><td>"+cart[i][0]+" "+cart[i][1]+" ("+cart[i][4]+") @ $"+cart[i][3].toFixed(2)+" ea</td>"; 
		HTML += "<td>"+cart[i][2]+"</td>"; 
		HTML += "<td>$"+total+"</td></tr>";
	}
	HTML += "</tbody></table>"; 
	document.getElementById('cart_table').innerHTML = HTML;
}


function addToCart() {
	var item = document.getElementById('item_select').value;
	var size = document.getElementById('size_select').value;
	var quantity = document.getElementById('quantity_select').value;
	var instr = document.getElementById('instr').value;
	var price = getPrice(item, size);
	if (item != "" && size != "" && quantity > 0) {
		var temp = [size, item, quantity, price, instr];
		cart[cart.length] = temp;
		document.getElementById('item_title').innerHTML = "Item: ";
		document.getElementById('item_select').value = "";
		document.getElementById('size_select').value = "";
		document.getElementById('quantity_select').value = "";
		document.getElementById('unit_cost').value = "";
		document.getElementById('instr').value = "";
		document.getElementById('total_title').innerHTML = "Total: $"+getCartTotal();
		drawCartTable();
	}
}


function changeUnitCost() {
	var item = document.getElementById('item_select').value;
	var size = document.getElementById('size_select').value;
	var price = getPrice(item, size);
	var str1 = "$";
	var str2 = "Item: ";
	if (price != undefined) {
		document.getElementById('unit_cost').value = str1.concat(price.toFixed(2));
		document.getElementById('item_title').innerHTML = str2.concat(size, " ", item, " @ $", price.toFixed(2));
	} else {
		document.getElementById('item_title').innerHTML = str2.concat(size, " ", item);
	}
}

function removeItem() {
	cart.pop();
	drawCartTable();
	var cartTotal = getCartTotal();
	if (cartTotal != 0) {
		document.getElementById('total_title').innerHTML = "Total: $"+cartTotal;
	} else {
		document.getElementById('total_title').innerHTML = "Total:";
	}
}

function emptyCart() {
	var length = cart.length;
	for (var i = 0; i < length; i++) {
		cart.pop();
	}
	drawCartTable();
	document.getElementById('total_title').innerHTML = "Total:";
	document.getElementById('discount').value = 0;
}

function applyDiscount() {
	discount = document.getElementById('discount').value;
	var cartTotal = getCartTotal();
	if (cart.length != 0) {
		document.getElementById('total_title').innerHTML = "Total: $"+cartTotal;
	}
}

function transferData() {
    if (cart.length > 0) {
		var URL = "includes/take_order.inc.php?order="+cart+"&disc="+discount/100;
		if (customer != undefined && phone != undefined) {
			URL += "&cust="+customer+"&phone="+phone;
		} else if (customer != undefined && phone == undefined) {
			URL += "&cust="+customer;
		} else if (customer == undefined && phone != undefined) {
			URL += "&phone="+phone;
		}
		window.location.href = URL;
	}
	emptyCart();
}

function saveCustomer() {
	customer = document.getElementById('cust').value;
}

function saveCustomerPhone() {
	phone = document.getElementById('cust_num').value;
}
