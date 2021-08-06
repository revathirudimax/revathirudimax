var cartData = [];
var deliveryData = [];
var total = 0.00;
var subtotal=0.00;
var dcost=50;
var couponcost=0;
var saving=0;

$(document).ready(function() {
    "use strict";

    updatepage(); 
    mycart();
    updateDates();
});

function addToCart(itemid,name,price,mrp,quantity,quantitytype,img) {

    let qty = "1";

	img = "https://gobasket.co.za/c/admin/itemimg/" + img;
    if(!checkCart(itemid)){
        cartData.push({itemid, qty, name, price, mrp, quantity, quantitytype,img});
        localStorage.setItem("cartData", JSON.stringify(cartData));
    }
    
    var btnid = '.'+'ADD'+itemid;
    var btnUid = '.'+'UPDATE'+itemid;
    var btnCid = '.'+'UPDATE'+itemid+' input[type="number"]';

    $(btnid).hide();
    $(btnUid).show();
    $(btnCid).val(getitemQuantity(itemid));

    mycart();
  }


  function getitemQuantity(itemid){
    for(let item of cartData){
      if(item.itemid == itemid)
      return item.qty;
    }
    return 0;
  }


  function changeProductQty(itemid, change){

    cartData = JSON.parse(localStorage.getItem('cartData'));

    let i = 0;
    for(let item of cartData){
      if(item.itemid == itemid){
        let qty = parseInt(item.qty);
        qty = qty + parseInt(change);
        if(qty > 0){
          item.qty = qty.toString();
        }else if(qty <= 0){   
          rmcart(itemid); 
          cartData.splice(i, 1);
        }
    }

    i++;
  }

  localStorage.setItem("cartData", JSON.stringify(cartData));
  var btnCid = '.'+'UPDATE'+itemid+' input[type="number"]';
  $(btnCid).val(getitemQuantity(itemid));

  if(localStorage.getItem('cartData')){
    cartData = JSON.parse(localStorage.getItem('cartData'));
      if(cartData.length < 1){
        var btnUid = '.'+'UPDATE'+itemid;
        $(btnUid).hide();
      }
    }

    updatepage();
    mycart();
}


function checkCart(itemid){

  cartData = JSON.parse(localStorage.getItem('cartData'));

    var result;
    for (var val of cartData) {
      if(val.itemid == itemid){
          result = true;
          break;
      }
      else{
        result = false
      }
    }
    return result;
  }

  function rmcart(itemid){

    var btnid = '.'+'ADD'+itemid;
    var btnUid = '.'+'UPDATE'+itemid;

    $(btnUid).hide();
    $(btnid).show();

    mycart();
}


  function mycart(){

    total = 0.00;
    subtotal=0.00;
    saving = 0.00;

    cartData = JSON.parse(localStorage.getItem('cartData'));

    for (var x = 0; x < cartData.length; x++) {
        subtotal = subtotal + (parseFloat(cartData[x].price) * parseInt(cartData[x].qty));
        saving = saving + ((parseFloat(cartData[x].mrp) - parseFloat(cartData[x].price)) * parseInt(cartData[x].qty));
    }

    total = subtotal + dcost;


   $("#scurite-cost span strong").text(Math.round(subtotal* 100) / 100);
   $("#scurite-dcost span strong").text(dcost);  
   $("#scurite-saving span strong").text(Math.round(saving* 100) / 100);
   $("#scurite-total span strong").text(Math.round(total* 100) / 100);

   cartitems();
   updatecartcount();
  }


  function cartitems(){

    let rawhtml = '';

    cartData = JSON.parse(localStorage.getItem('cartData'));

    for (var x = 0; x < cartData.length; x++) {
    
      rawhtml += `
      
      <div class="cart-item">
      <div class="cart-product-img">
        <img src="`+ cartData[x].img+`" alt="" />
      </div>
      <div class="cart-text">
        <h4>`+ cartData[x].name+`</h4>
        <div class="qty-group">
          <div class="quantity buttons_added" id="UPDATE`+cartData[x].itemid+`">
            <input type="button" value="-" class="minus minus-btn" onclick="changeProductQty('`+cartData[x].itemid+`','-1')"/>
            <input type="number" step="1" name="quantity" value="`+getitemQuantity(cartData[x].itemid)+`" class="input-text qty text" />
            <input type="button" value="+" class="plus plus-btn" onclick="changeProductQty('`+cartData[x].itemid+`','+1')"/>
          </div>
          <div class="cart-item-price">R`+cartData[x].price+`<span>R`+cartData[x].mrp+`</span></div>
        </div>
       
        <button type="button" class="cart-close-btn"><i class="uil uil-multiply"></i></button>
      </div> </div>`;
    }

    $(".side-cart-items").html(rawhtml);

  }

  function getotp(){

        $.ajax({
         url: '/get',
         type: "POST",
         data: {
                  userid : login,
                  itemid:username,
                  qty:password
              },
           success: function(response){   
          
           }
        });
  
}



  function placeorder(){

    let pm = $('input[name=paymentmethod]:checked').val();

    let postCart = [];
    let postUserData = [];
    let postDeliveryData = [];

    if(localStorage.getItem('domain')){
      domainData = JSON.parse(localStorage.getItem('domain'));

      if(localStorage.getItem('cartData')){
        postCart = JSON.parse(localStorage.getItem('cartData'));
      }
      if(localStorage.getItem('userData')){
        postUserData = JSON.parse(localStorage.getItem('userData'));
      }
      if(localStorage.getItem('dt')){
        postDeliveryData = JSON.parse(localStorage.getItem('dt'));
      }

      $.ajax({
        url: domainData.url+'/u/placeorder',
        type: "post",
        data: {items:postCart,userData:postUserData,deliveryData:postDeliveryData,pm:pm},
          success: function(response){ 
            console.log(response);
            let temp = JSON.parse(response)
            if(temp.status == 'success'){
              window.location = domainData.url+'/u/od/'+temp.order;
              localStorage.removeItem('cartData');
              localStorage.removeItem('userData');
              localStorage.removeItem('dt');
            }else{
              console.log('Unable to Place Order');
            }
          }
      });

    }else{
      domainData = "null"
    }
  }


  function updatepage(){

    $(".quantity").hide();

    if(localStorage.getItem('cartData')){
      cartData = JSON.parse(localStorage.getItem('cartData'));

      for(let item of cartData){
          var btnid = '.'+'ADD'+item.itemid;
          var btnUid = '.'+'UPDATE'+item.itemid;
          var btnCid = '.'+'UPDATE'+ item.itemid +' input[type="number"]';

          $(btnid).hide();
          $(btnUid).show();        
          $(btnCid).val(getitemQuantity(item.itemid));
      }


    }else{
      localStorage.setItem("cartData", JSON.stringify(cartData));
    }

  }


  function updatecartcount(){
    $(".main-cart-title span").text(cartData.length + ' Items');
    $("#cartcount").text(cartData.length);

    if(cartData.length <= 0){
      $(".cart-checkout-btn").hide();
    }else{
      $(".cart-checkout-btn").show();
    }
  }


  function setcart(){
    if(localStorage.getItem('cartData')){
      cart = JSON.parse(localStorage.getItem('cartData'));
      $("#cart-items-input").val(JSON.stringify(cart));
      console.log(cart);
      return true;
    }
    return false;
  }


  function storeDeliveryData(form){
    var data = $(form).serializeArray();

    if(data[0].value != '' && data[1].value != '' && data[2].value != '' && data[3].value != '' && data[4].value != ''){
      localStorage.setItem("userData", JSON.stringify(data));
      $("#cpl3").attr("href", "#collapseThree");
    }
    return false; 
  }

  function updateDates(){

  var objToday = new Date(),

	dayOfMonth = today + ( objToday.getDate() < 10) ? '0' + objToday.getDate() : objToday.getDate(),
	months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
	curMonth = months[objToday.getMonth()],
	curYear = objToday.getFullYear(),

  today = dayOfMonth + " " + curMonth + " " + curYear;


  $('#dd1').val(dayOfMonth + " " + curMonth + " " + curYear);
  $('#ddl1').text('Today')

  $('#dd2').val(dayOfMonth+1 + " " + curMonth + " " + curYear);
  $('#ddl2').text('Tomorrow')

  $('#dd3').val(dayOfMonth+2 + " " + curMonth + " " + curYear);
  $('#ddl3').text(dayOfMonth+2 + " " + curMonth + " " + curYear)

  $('#dd4').val(dayOfMonth+3 + " " + curMonth + " " + curYear);
  $('#ddl4').text(dayOfMonth+3 + " " + curMonth + " " + curYear)

  $('#dd5').val(dayOfMonth+4 + " " + curMonth + " " + curYear);
  $('#ddl5').text(dayOfMonth+4 + " " + curMonth + " " + curYear)

  $('#dd6').val(dayOfMonth+5 + " " + curMonth + " " + curYear);
  $('#ddl6').text(dayOfMonth+5 + " " + curMonth + " " + curYear)

  $('#dd7').val(dayOfMonth+6 + " " + curMonth + " " + curYear);
  $('#ddl7').text(dayOfMonth+6 + " " + curMonth + " " + curYear)

  $('#dd8').val(dayOfMonth+7 + " " + curMonth + " " + curYear);
  $('#ddl8').text(dayOfMonth+7 + " " + curMonth + " " + curYear)

  }


  function storeDatetime(){
    date = $('input[name=date]:checked').val();
    time = $('input[name=time]:checked').val();
    dt = [{"date":date},{"time":time}];
    localStorage.setItem("dt", JSON.stringify(dt));
    return false;
  }

