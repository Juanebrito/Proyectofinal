

var estudianteTemplate = `
    <tr id="row-estudiante-{{ID}}">
    <td>{{NOMBRE}}</td>
    <td>{{MATRICULA}}</td>
    <td>{{EDAD}}</td>
    <td>
        <button id="editar-{{ID}}" onclick='editar({{ID}})' data-estudiante='{{DATA}}' class="btn btn-warning">Editar</button> | 
        <button onclick="eliminar({{ID}})" class="btn btn-danger">Eliminar</button>
    </td>
    </tr>
`

function buscarEstudiante() {
    fetch("../estudiante1.php")
        .then( res => res.json())
        .then( res => {
            console.log(res);
            var listaM = document.getElementById('list_estudiante');
            var temp = '';
            res.forEach(m => {
                temp = temp + estudianteTemplate.replace(/{{NOMBRE}}/, m.nombre)
                    .replace(/{{ID}}/g, m.id)
                    .replace(/{{MATRICULA}}/, m.matricula)
                    .replace(/{{EDAD}}/, m.edad)
                    .replace(/{{DATA}}/, JSON.stringify(m));

            });
            listaM.innerHTML = temp;
        })
        .catch( err => {
            console.log(err);
        });
}

var estudiante = null;

function guardar(){

    nombre = document.getElementById("nombre").value;
    matricula = document.getElementById("matricula").value;
    edad = document.getElementById("edad").value;

    var nueva = true;
    if (estudiante != null && estudiante.id ){
        nueva = false;
        var btnEditar = document.getElementById("editar-"+estudiante.id);
    } else {
        estudiante = {};
    }
     estudiante.nombre = nombre;
     estudiante.matricula = matricula;
     estudiante.edad = edad;
    console.log(estudiante);
   
    if (nueva == false) {
        btnEditar.dataset.estudiante = JSON.stringify(estudiante);
    }

   fetch('../estudiante1.php'+(nueva ? '' : `?id=${estudiante.id}`), {
        method: (nueva ? 'POST' : 'PUT'),
        body: JSON.stringify(estudiante),
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


    estudiante = null;
}

function editar(id){

    var btnEditar = document.getElementById("editar-"+id);

    var data = btnEditar.dataset.estudiante;
    estudiante = JSON.parse(data);

    document.getElementById("nombre").value = estudiante.nombre;
    document.getElementById("matricula").value = estudiante.matricula;
    document.getElementById("edad").value = estudiante.edad;
}


function eliminar(id){
    fetch(`../estudiante1.php?id=${id}`, {
        method: 'DELETE'
    })
    .then( res => res.json())
    .then( res => {
        var row = document.getElementById("row-estudiante-"+id).rowIndex;
console.log(row);
        document.getElementById('list_estudiante').deleteRow(row-1);
        console.log(res);
    })
    .catch( err => {
        console.log(err);
    });
    

}


window.onload = function(){
    this.buscarEstudiante();

    document.getElementById("guardarestudiante").addEventListener("click", guardar);
}
