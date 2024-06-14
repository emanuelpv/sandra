// JavaScript Document
var this_js_script = $('script[src*=privacidade]'); 

var url = this_js_script.attr('url');   
if (typeof url === "undefined" ) {
   var url = '';
}
	
var domain = this_js_script.attr('domain');   
if (typeof domain === "undefined" ) {
   var domain = '';
}

if(!getStatusBrowser(domain)){
	getPermission();
}

//	denyBrowser(domain);
function getPermission() {
	
	if(url){
	
		var statementURL = "<a style='color:#fff; hover:#fff' href='"+ url +"'>Política de Privacidade.</a>";

		var textModal = "Utilizamos Cookies para que você possa desfrutar dos login de tecnologia deste site. Seus dados pessoais poderão ser solicitados para a utilização de nossos serviços. Ao navegar em nosso site ou utilizar nossos serviços você concorda automaticamente com nossa ";

		$("body").after("<div id='getPermissionModal' style='width:90%; bottom:30px; left:5%;z-index:100000; position: fixed ;color:#cccccc; border:none;border-radius: 10px; padding:20px; background-color:#666666; font-size:14px'><div class='row'><div class=' col-xs-12 col-sm-10 '>" + textModal + " " + statementURL + "</div><div class='col-xs-4 col-sm-2' style='padding-top:5px'><button class='btn btn-success btn-xs col-xs-12' onclick='allowBrowser(\""+ domain +"\")' >Concordar e Fechar</button></div></div></div>");
	}
}

	
function allowBrowser(dominio) {
	var nome = 'ga_'+dominio;
	var valor = dominio;
    valor = encodeURI(valor);
    document.cookie = nome + '=' + valor + '; path=/';
	$("#getPermissionModal").hide();
}

function denyBrowser(dominio){
	var nome = 'ga_'+dominio;
    var data = new Date(2010,0,01);
    data = data.toGMTString();
    document.cookie = nome + '=; expires=' + data + '; path=/';
}


function getStatusBrowser(dominio) {

    var cname = ' ga_'+dominio+'=';
    var cookies = document.cookie;
    if (cookies.indexOf(cname) == -1) {
        return false;
    }    
    cookies = cookies.substr(cookies.indexOf(cname), cookies.length);
    if (cookies.indexOf(';') != -1) {
        cookies = cookies.substr(0, cookies.indexOf(';'));
    }
    cookies = cookies.split('=')[1];  
	var res = decodeURI(cookies);
	
	return res;

		
}	
	