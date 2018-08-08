var pagina = 1;
var filas = 10;
var auxFilter="";
var filter = "";
var tWait;
var extraFilter="";
function loadTable(data,idTable){
	document.getElementById(idTable+"_loading").style.display = "none";
    

if(data.total > 0){

	    pagina = data.pagina;

		var tbl = document.getElementById(idTable);
	    var tbody = tbl.getElementsByTagName("tbody");
		var pag = document.getElementById(idTable+"_pag");
		
		var maxSides = 3;
		var pags = Math.ceil(data.total / filas);
		var active =pagina;
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

		var prevBtn = '<li class="page-item disabled"><a class="page-link" id="table_prev" onClick="prevTbl(this)" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li><li class="page-item active"><a class="page-link" onClick=\"setPag(1,this);\">1 <span class="sr-only">(current)</span></a></li>';

		var nextBtn = '<li class="page-item"><a class="page-link" id="table_next" onClick="nextTbl(this)" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>';

		var puntosBtn = "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"javascript:void(0);\"> ... </a></li>";

		numPages=numPages+( 1 == active ? prevBtn : prevBtn.replace('disabled','').replace('page-item active','page-item') + ((parseInt(active)-(parseInt(maxSides)))>2 ? puntosBtn : '') );
		
		for (ix=0; ix < maxSides; ix++) { 
			num = (parseInt(active)-(parseInt(maxSides))+parseInt(ix));
			if(num>1) numPages=numPages+"<li class=\"page-item\"><a class=\"page-link\" onClick=\"setPag("+num+",this);\">"+num+"</a></li>";
		}

		if(active>1 && active<pags) numPages= numPages + "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">"+active+" <span class=\"sr-only\">(current)</span></a></li>";

		for (iy=(parseInt(active)+1); iy < (parseInt(active)+parseInt(maxSides)+1); iy++) { 
			num = iy;	
			if(num<pags) numPages=numPages+"<li class=\"page-item\"><a class=\"page-link\" onClick=\"setPag("+num+",this);\">"+num+"</a></li>";
		}

		if(pags>1)

			numPages=numPages+(pags==active? (num<pags-1 ? puntosBtn : '')+'<li class="page-item active"><a class="page-link" href="#">'+pags+' <span class="sr-only">(current)</span></a></li>':(num<(pags-1) ? puntosBtn : '')+"<li class=\"page-item\"><a class=\"page-link\" onClick=\"setPag("+pags+",this);\">"+pags+"</a></li>" + nextBtn );

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


      if (typeof cbTabla === "function") { 
        cbTabla(data,idTable);
      }


}

function nextTbl(element){
	var idTable = element.parentElement.parentElement.id.replace("_pag","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
 	var mReq = new XMLHttpRequest();
    mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
    mReq.open("GET", modulo+"&filas="+filas+"&filter="+filter+"&pagina="+parseInt(parseInt(pagina)+1));
    mReq.send();
}

function prevTbl(element){
	var idTable = element.parentElement.parentElement.id.replace("_pag","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
 	var mReq = new XMLHttpRequest();
    mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
	mReq.open("GET", modulo+"&filas="+filas+"&filter="+filter+"&pagina="+parseInt(parseInt(pagina)-1));
    mReq.send();
}

function setPag(np,element){
 	var idTable = element.parentElement.parentElement.id.replace("_pag","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
 	var mReq = new XMLHttpRequest();
    mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
	mReq.open("GET", modulo+"&filas="+filas+"&filter="+filter+"&pagina="+np);
    mReq.send();
}

function setFilas(mfilas,idTable){
	var idTable = idTable.replace("_filas","");
	var modulo = document.getElementById(idTable).getAttribute("data-source");
 	filas = mfilas;
	pagina=1;
	setFilter(filter,idTable);
}

function setFilter(mfilt,idTbl){
	idTbl = idTbl.replace("_filter","");
	var modulo = document.getElementById(idTbl).getAttribute("data-source");
	pagina = (filter != mfilt ? 1 : pagina);

	filter = mfilt+extraFilter;

	clearTimeout(tWait);
	tWait=setTimeout(function() {
		document.getElementById(idTbl+"_loading").style.display = "block";
		var mReq = new XMLHttpRequest();
        mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTbl);});
		mReq.open("GET", modulo+"&filas="+filas+"&filter="+filter+"&pagina="+pagina);
        mReq.send();
	}, 350);
}

function initTbl(id){
	var idTable = id;
	var elmTbl = document.getElementById(idTable);
	if(elmTbl != null){
		var modulo = elmTbl.getAttribute("data-source");
		if(modulo!=null && modulo!='null' && modulo!='undefined'){
			var mReq = new XMLHttpRequest();
			mReq.addEventListener("load", function(){loadTable(JSON.parse(this.responseText),idTable);});
			mReq.open("GET", modulo+"&filas="+filas+"&filter="+filter+"&pagina="+1);
			mReq.send();
		}
	}
}

var tablas = document.getElementsByClassName("table");
var iwww;
for (iwww = 0; iwww < tablas.length; iwww++) {
	initTbl(tablas[iwww].id);
}


	