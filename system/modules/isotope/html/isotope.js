var Isotope={toggleAddressFields:function(a,b){if(a.value=="0"&&a.checked){$(b).setStyle("display","block")}else{$(b).setStyle("display","none")}},displayBox:function(e){var d=$("iso_ajaxBox");var b=$("iso_ajaxOverlay");if(!b){b=new Element("div").setProperty("id","iso_ajaxOverlay").injectInside($(document.body))}if(!d){d=new Element("div").setProperty("id","iso_ajaxBox").injectInside($(document.body))}var a=window.getScroll().y;if(Browser.Engine.trident&&Browser.Engine.version<5){var f=$$("select");for(var c=0;c<f.length;c++){f[c].setStyle("visibility","hidden")}}b.setStyle("display","block");b.setStyle("top",a+"px");d.set("html",e);d.setStyle("display","block");d.setStyle("top",(a+100)+"px")},hideBox:function(){var c=$("iso_ajaxBox");var a=$("iso_ajaxOverlay");if(a){a.setStyle("display","none")}if(c){c.setStyle("display","none");if(Browser.Engine.trident&&Browser.Engine.version<5){var d=$$("select");for(var b=0;b<d.length;b++){d[b].setStyle("visibility","visible")}}}}};var IsotopeProduct=new Class({Implements:Options,Binds:["refresh"],options:{language:"en",loadMessage:"Loading product data …"},initialize:function(c,d,a,b){this.setOptions(b);this.form=document.id(("iso_product_"+d)).set("send",{url:("ajax.php?action=fmd&id="+c+"&language="+this.options.language+"&product="+d),link:"cancel",onRequest:function(){Isotope.displayBox(this.options.loadMessage)}.bind(this),onSuccess:function(e,f){Isotope.hideBox();JSON.decode(e).each(function(h){var g=document.id(h.id);if(g){var i=new Element("div").set("html",h.html).getFirst(("#"+h.id));if(i){i.cloneEvents(g).replaces(g)}}});window.fireEvent("ajaxready")},onFailure:function(){Isotope.hideBox()}});a.each(function(f,e){if($(f)){$(f).addEvent("change",this.refresh)}}.bind(this))},refresh:function(a){this.form.send()}});