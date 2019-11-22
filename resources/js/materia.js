


var materiaTemplate = `
<tr id="row-materia-{{ID}}">
<td>{{NOMBRE}}</td>
<td>{{CREDITOS}}</td>
<td>
    <button id="editar-{{ID}}" onclick='editar({{ID}})' data-materia='{{DATA}}' class="btn btn-warning">Editar</button> | 
    <button onclick="eliminar({{ID}})" class="btn btn-danger">Eliminar</button>
</td>
</tr>
`

function buscarMateria() {
fetch("../views/materia.php")
    .then( res => res.json())
    .then( res => {
        console.log(res);
        var listaM = document.getElementById('list_materia');
        var temp = '';
        res.forEach(m => {
            temp = temp + materiaTemplate.replace(/{{NOMBRE}}/, m.nombre)
                .replace(/{{ID}}/g, m.id)
                .replace(/{{CREDITOS}}/, m.creditos)
                .replace(/{{DATA}}/, JSON.stringify(m));

        });
        listaM.innerHTML = temp;
    })
    .catch( err => {
        console.log(err);
    });
}

var materia = null;

function guardar(){

nombre = document.getElementById("nombre").value;
creditos = document.getElementById("creditos").value;

var nueva = true;
if (materia != null && materia.id ){
    nueva = false;
    var btnEditar = document.getElementById("editar-"+materia.id);
} else {
    materia = {};
}
 materia.nombre = nombre;
 materia.creditos = creditos;

console.log(materia);

if (nueva == false) {
    btnEditar.dataset.materia = JSON.stringify(materia);
}

fetch('../views/materia.php'+(nueva ? '' : `?id=${materia.id}`), {
    method: (nueva ? 'POST' : 'PUT'),
    body: JSON.stringify(materia),
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


materia = null;
}

function editar(id){

var btnEditar = document.getElementById("editar-"+id);

var data = btnEditar.dataset.estudiante;
estudiante = JSON.parse(data);

document.getElementById("nombre").value = materia.nombre;
document.getElementById("creditos").value = materia.creditos;



function eliminar(id){
fetch(`../views/materia.php?id=${id}`, {
    method: 'DELETE'
})
.then( res => res.json())
.then( res => {
    var row = document.getElementById("row-materia-"+id).rowIndex;
console.log(row);
    document.getElementById('list_materia').deleteRow(row-1);
    console.log(res);
})
.catch( err => {
    console.log(err);
});


}


window.onload = function(){
this.buscarMateria();

document.getElementById("guardarmateria").addEventListener("click", guardar);
}
}