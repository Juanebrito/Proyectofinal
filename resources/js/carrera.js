


var carreraTemplate = `
<tr id="row-carrera-{{ID}}">
<td>{{NOMBRE}}</td>

<td>
    <button id="editar-{{ID}}" onclick='editar({{ID}})' data-carrera='{{DATA}}' class="btn btn-warning">Editar</button> | 
    <button onclick="eliminar({{ID}})" class="btn btn-danger">Eliminar</button>
</td>
</tr>
`

function buscarCarrera() {
fetch("../carrera.php")
    .then( res => res.json())
    .then( res => {
        console.log(res);
        var listaM = document.getElementById('list_carrera');
        var temp = '';
        res.forEach(m => {
            temp = temp + carreraTemplate.replace(/{{NOMBRE}}/, m.nombre)
                .replace(/{{ID}}/g, m.id)
                .replace(/{{DATA}}/, JSON.stringify(m));

        });
        listaM.innerHTML = temp;
    })
    .catch( err => {
        console.log(err);
    });
}

var carrera = null;

function guardar(){

nombre = document.getElementById("nombre").value;

var nueva = true;
if (carrera != null && carrera.id ){
    nueva = false;
    var btnEditar = document.getElementById("editar-"+carrera.id);
} else {
    carrera= {};
}
 carrera.nombre = nombre;
 console.log(carrera);

if (nueva == false) {
    btnEditar.dataset.carrera = JSON.stringify(carrera);
}

fetch('../carrera.php'+(nueva ? '' : `?id=${carrera.id}`), {
    method: (nueva ? 'POST' : 'PUT'),
    body: JSON.stringify(carrera),
    headers: {
        'Content-Type': 'application/json'
      }
})
.then( res => res.json())
.then( res => {
    console.log(res);
})
.catch( err => {
    console.log(err);
});


carrera = null;
}

function editar(id){

var btnEditar = document.getElementById("editar-"+id);

var data = btnEditar.dataset.carrera;
carrera = JSON.parse(data);

document.getElementById("nombre").value = carrera.nombre;

}


function eliminar(id){
fetch(`../carrera.php?id=${id}`, {
    method: 'DELETE'
})
.then( res => res.json())
.then( res => {
    var row = document.getElementById("row-carrera-"+id).rowIndex;
console.log(row);
    document.getElementById('list_carrera').deleteRow(row-1);
    console.log(res);
})
.catch( err => {
    console.log(err);
});


}


window.onload = function(){
this.buscarCarrera();

document.getElementById("guardarcarrera").addEventListener("click", guardar);
}
