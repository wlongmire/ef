//helper functions
String.prototype.keyPair = function() {
  //changes GET style value pairs into js objects

  var val = this.split("=");
  var obj = {};
  obj[val[0]]= val[1];

  return(obj);
}


$.range = function(start, end, step) {
  //returns an array of ints from start to end step by the optional step value
  var rtn = [];

  if (step === undefined)
    step = 1;

  for (var i = start; i<end; i+= step) {
    rtn.push(i);
  }
  return(rtn);
}

$.zeroPadding = function(str, amount, right, char)
{
  //returns a version of str padded with char if str's length is less then amount
  var rtn = str;
  if (char === undefined)
      char = "0";

  if (right === undefined)
      right = true;

  if (rtn.length < amount) {
    $.each($.range(0,amount-rtn.length), function(index, value){
      if (right)
        rtn = rtn + char;
      else
        rtn = char + rtn;
    });
  }

  return rtn;
}

$.createMoney = function(s) {
  //adds a dollor sign and converts remove cents if zero
    var rtn = s;

    if (Number(rtn)) {
      var period = rtn.indexOf(".");
      if(rtn.substr(period + 1, rtn.length) === "00") {
        rtn = rtn.substr(0, period);
      }
      rtn = "$" + rtn;
    }

    return(rtn);
}

$.objectLength = function(obj)
  //returns the amount of (non function) attributes within on object. Handwavy
  {
  var i = 0;
  for ( var p in this )
  {
      if ( 'function' === typeof this[p] ) continue;
      i++;
  }

  return i;
}

$.restrictKeys = function(obj, validKeys) {
  //returns a version of object containing only properties contained within validkeys
  var rtn = {};
  
  $.each(obj, function(index, val){
    if ($.inArray(index, validKeys) !== -1) {
      rtn[index] = val;
    }
  })
  return(rtn);
}

$.stringToBool = function(s){
  //converts a string to an approprate boolean
  return(s === "yes" || s === "true");
}