
window.onload = function(){
    var formulario = document.getElementById('formulario');
    formulario.addEventListener('submit',(e) =>{
        const url = '/estudiante1.php';
        // e.preventDefault();

        const datosPost = {
            nombre : document.getElementById("nombre").value,
            matricula : document.getElementById("matricula").value,
            edad : document.getElementById("edad").value
        };
        
        // console.log(datosPost);
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(datosPost), // data can be `string` or {object}!
            headers:{
              'Content-Type': 'application/text'
            }
          }).then(res => res.text())
          .catch(error => console.error('Error:', error))
          .then(response => console.log('Success:', response));
        
    });
    
}