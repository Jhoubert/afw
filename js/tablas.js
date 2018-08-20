<script type="text/javascript">

/*
 * Autor: Jhoubert Rincon
 * Fecha: 10 Enero 2017
 * Actualizado: 01 Junio 2018
 *  - 	Veflat,C.A
*/

var defaultFilas = 10;
var objFilters = {};
var objFilas = {};
var objPaginas = {};
var objTWait = {};


var BtnAnterior = "<b>&lt;</b>";
var BtnSiguiente = "<b>&gt;</b>";
var BtnPuntos = "...";


/*

preRules:function(data,idtable){

	// Manipula los datos recibidos

	// RETORNA la variable data nuevamente
	
}


posRules:function(data,idTable){
	
	// Manipula los datos despues de pintarlos

	// NO retorna nada

}

*/


function loadTable(data,idTable){

	document.getElementById(idTable+"_loading").style.display = "none";
	
	if(data.total > 0){

		if (typeof preRules === "function") { 
			data.data = preRules(data.data,idTable);
		}

	    objPaginas[idTable] = data.pagina;

		var tbl = document.getElementById(idTable);
	    var tbody = tbl.getElementsByTagName("tbody");
		var pag = document.getElementById(idTable+"_pag");
		
		var maxSides = 3;
		var pags = Math.ceil(data.total / objFilas[idTable]);
		var active =objPaginas[idTable];
		var numPages = "";
		var tml="";
		var num=0;

		tbody[0].innerHTML="";

		for (var i = 0; i < data.filas; i++) {
		    var keys = [];
		    var tds = "";
		    for(var mKey in data.data[i]) tds = tds + "<td>"+data.data[i][mKey]+"</td>";
			tml = tml+'<tr>'+tds+'</tr>';
		}

		tbody[0].innerHTML=tml;

		var prevBtn = '<li class="page-item disabled"><a class="page-link" id="table_prev" onClick="prevTbl(this)" aria-label="Previous"><span aria-hidden="true">'+BtnAnterior+'</span><span class="sr-only">Previous</span></a></li><li class="page-item active"><a class="page-link" onClick=\"setPag(1,this);\">1 <span class="sr-only">(current)</span></a></li>';

		var nextBtn = '<li class="page-item"><a class="page-link" id="table_next" onClick="nextTbl(this)" aria-label="Next"><span aria-hidden="true">'+BtnSiguiente+'</span><span class="sr-only">Next</span></a></li>';

		var puntosBtn = "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"javascript:void(0);\"> "+BtnPuntos+" </a></li>";

		numPages=numPages+( 1 == active ? prevBtn : prevBtn.replace('disabled','').replace('page-item active','page-item') + ((parseInt(active)-(parseInt(maxSides)))>2 ? puntosBtn : '') );
		
		for (ix=0; ix < maxSides; ix++) { 
			num = (parseInt(active)-(parseInt(maxSides))+parseInt(ix));
			if(num>1) numPages=numPages+"<li class=\"page-item\"><a class=\"page-link\" onClick=\"setPag("+num+",this);\">"+num+"</a></li>";
		}

		if(active>1 && active<pags) numPages= numPages + "<li class=\"page-item active\"><a class=\"page-link\" href=\"javascript:void(0);\">"+active+" <span class=\"sr-only\">(current)</span></a></li>";

		for (iy=(parseInt(active)+1); iy < (parseInt(active)+parseInt(maxSides)+1); iy++) { 
			num = iy;	
			if(num<pags) numPages=numPages+"<li class=\"page-item\"><a class=\"page-link\" onClick=\"setPag("+num+",this);\">"+num+"</a></li>";
		}

		if(pags>1)

			numPages=numPages+(pags==active? (num<pags-1 ? puntosBtn : '')+'<li class="page-item active"><a class="page-link" href="javascript:void(0);">'+pags+' <span class="sr-only">(current)</span></a></li>':(num<(pags-1) ? puntosBtn : '')+"<li class=\"page-item\"><a class=\"page-link\" onClick=\"setPag("+pags+",this);\">"+pags+"</a></li>" + nextBtn );

		pag.innerHTML=numPages; 

	}
	else{
		var tbl = document.getElementById(idTable);
	    var tbody = tbl.getElementsByTagName("tbody");
		var pag = document.getElementById(idTable+"_pag");
		cols = document.getElementById(idTable).rows[0].cells.length;

		
		tbody[0].innerHTML='<tr><td colspan="'+cols+'" align="center"><br><b>No hay registros para mostrar</b></td></tr>';
		pag.innerHTML="<li class=\"page-item disabled\"><a class=\"page-link\" href=\"javascript:void(0);\"> 0 </a></li>"; 


	}


	if (typeof posRules === "function") { 
		posRules(data,idTable);
	}
	
}


function isGet(method){
	return (method.toLowerCase()=="get" ? true : false );
}
function isPost(method){
	return (method.toLowerCase()=="post" ? true : false );
}

function nextTbl(element){
	var idTable = element.parentElement.parentElement.id.replace("_pag","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
	var method = document.getElementById(idTable).getAttribute("data-method");
	var datos = "filas="+objFilas[idTable]+"&filter="+objFilters[idTable]+"&pagina="+parseInt(parseInt(objPaginas[idTable])+1);
 	var mReq = new XMLHttpRequest();
    mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
    mReq.open(method, modulo+(isGet(method)?"&"+datos:''),true);
    mReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    mReq.send((isPost(method)?datos:''));
}

function prevTbl(element){
	var idTable = element.parentElement.parentElement.id.replace("_pag","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
	var method = document.getElementById(idTable).getAttribute("data-method");
 	var datos = "filas="+objFilas[idTable]+"&filter="+objFilters[idTable]+"&pagina="+parseInt(parseInt(objPaginas[idTable])-1);
 	var mReq = new XMLHttpRequest();
    mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
	mReq.open(method, modulo+(isGet(method)?"&"+datos:''),true);
	mReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    mReq.send((isPost(method)?datos:''));
}

function setPag(np,element){
 	var idTable = element.parentElement.parentElement.id.replace("_pag","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
	var method = document.getElementById(idTable).getAttribute("data-method");
	var datos = "filas="+objFilas[idTable]+"&filter="+objFilters[idTable]+"&pagina="+np;
 	var mReq = new XMLHttpRequest();
    mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
	mReq.open(method, modulo+(isGet(method)?"&"+datos:''),true);
	mReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    mReq.send((isPost(method)?datos:''));
}

function setFilas(mfilas,idTable){
	var idTable = idTable.replace("_filas","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
	var method = document.getElementById(idTable).getAttribute("data-method");
	objFilas[idTable] = mfilas;
	objPaginas[idTable]=1;
	setFilter(objFilters[idTable],idTable);
}

function setFilter(mfilt,idTable){

	idTable = idTable.replace("_filter","");

	var modulo = document.getElementById(idTable).getAttribute("data-source");
	var method = document.getElementById(idTable).getAttribute("data-method");
	objPaginas[idTable] = (objFilters[idTable] != mfilt ? 1 : objPaginas[idTable]);

	objFilters[idTable] = mfilt;
	clearTimeout(objTWait[idTable]);
	objTWait[idTable]=setTimeout(function() {
		document.getElementById(idTable+"_loading").style.display = "block";
		var datos = "filas="+objFilas[idTable]+"&filter="+objFilters[idTable]+"&pagina="+objPaginas[idTable];
		var mReq = new XMLHttpRequest();
        mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
		mReq.open(method, modulo+(isGet(method)?"&"+datos:''),true);
		mReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        mReq.send((isPost(method)?datos:''));
	}, 350);
}

var mReq = {};
function initTbl(id){
	console.log("initializing... "+ id );
	var idTable = id;
	var tableObj = document.getElementById(idTable);
	if(tableObj != null){
		var modulo = tableObj.getAttribute("data-source");
		var method = tableObj.getAttribute("data-method");
		if(modulo!=null && modulo!='null' && modulo!='undefined'){
			mReq[id] = new XMLHttpRequest();
			
			mReq[id].addEventListener("load", function(){ 
				loadTable(JSON.parse(this.responseText),idTable);
			});
			objFilters[id]="";
			objFilas[id] = defaultFilas;
			objPaginas[id] = 1;
			objTWait[id] = null;

			var datos = "filas="+objFilas[idTable]+"&filter="+objFilters[idTable]+"&pagina="+1;
			mReq[id].open(method, modulo+(isGet(method)?"&"+datos:''));
			mReq[id].setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			mReq[id].send((isPost(method)?datos:''));

		}
	}else{
		console.log("404 Table NOT FOUND.." + id);
	}
}

function reloadTable(idTable){
	setFilter(objFilters[idTable],idTable);
}

var tablas = document.getElementsByClassName("table");
var iwww;
for (iwww = 0; iwww < tablas.length; iwww++) {
	initTbl(tablas[iwww].id);
}



</script>