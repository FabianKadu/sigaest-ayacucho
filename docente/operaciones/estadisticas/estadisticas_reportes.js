function obtenerProceso() {
    // Obtener la URL actual
    var url = window.location.href;
    // Obtener solo el query string (después del signo de interrogación)
    var queryString = url.split('?')[1];
    // Obtener los parámetros separados
    var parametros = queryString.split('&');
    // Inicializar variable para el id
    var id;
  
    // Iterar sobre los parámetros
    parametros.forEach(function(parametro) {
      // Dividir el parámetro en nombre y valor
      var partes = parametro.split('=');
      var nombre = partes[0];
      var valor = partes[1];
      // Si el nombre del parámetro es 'id', asignar el valor a la variable id
      if (nombre === 'id') {
        id = valor;
        return false; // Salir del bucle forEach ya que encontramos el id
      }
    });
    return id;
};

function pintarGrafico0() {
    $.ajax({
        type: "POST",
        url: "operaciones/estadisticas/obtener_postulantes_.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);


        }
    });
}

$(document).ready(function () {
    id = obtenerProceso();
    pintarGrafico1();
    pintarGrafico2();
    pintarGrafico3();
    pintarGrafico4();
    pintarGrafico5();
    pintarGrafico7();
    pintarGrafico8();
    console.log(id);
})

// Generar colores aleatorios
function generarColoresAleatorios(numColores) {
    const colores = [];
    for (let i = 0; i < numColores; i++) {
        const color = `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.6)`;
        colores.push(color);
    }
    return colores;
}

// GRAFICO 1 PROCESOS DE ADMISION

function pintarGrafico1() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_mediosDifusion_proceso.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);


            var ctxLine = document.getElementById('grafico1').getContext('2d');

            var labels = llaves;
            var data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Postulantes',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: valores
                    },
                ]
            };

            var config = {
                type: 'radar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        // title: {
                        //     display: true,
                        //     text: 'Chart.js Radar Chart'
                        // }
                    }
                },
            };


            const myChart = new Chart(ctxLine, config);


        }
    });
}

// GRAFICO 2 - PROGRAMAS DE ESTUDIO
function pintarGrafico2() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_programaEstudios_proceso.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);

            ctxLine = document.getElementById('grafico2').getContext('2d');

            data = {
                labels: llaves,
                datasets: [
                    {
                        label: 'Postulantes: ',
                        backgroundColor: generarColoresAleatorios(llaves.length),
                        borderWidth: 1,
                        data: valores,
                    }
                ]
            };

            config = {
                type: 'polarArea',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        // title: {
                        //     display: true,
                        //     text: 'Chart.js Polar Area Chart'
                        // }
                    }
                },
            };

            const myChart2 = new Chart(ctxLine, config);

        }
    });
}

// GRAFICO 3 - MODALIDADES

function pintarGrafico3() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_modalidades_proceso.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);

            ctxLine = document.getElementById('grafico3').getContext('2d');

            data = {
                labels: llaves,
                datasets: [
                    {
                        label: 'Postulantes',
                        backgroundColor: 'rgb(125, 175, 245)',
                        // borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: valores
                    },
                ]
            };

            config = {
                type: 'bar',
                data: data,
                options: {
                    indexAxis: 'y',
                    // Elements options apply to all of the options unless overridden in a dataset
                    // In this case, we are setting the border of each horizontal bar to be 2px wide
                    elements: {
                        bar: {
                            borderWidth: 2,
                        }
                    },
                    responsive: true,
                    // plugins: {
                    //     legend: {
                    //         position: 'right',
                    //     },
                    //     // title: {
                    //     //     display: true,
                    //     //     text: 'Chart.js Horizontal Bar Chart'
                    //     // }
                    // }
                },
            };

            const myChart3 = new Chart(ctxLine, config);

        }
    });
}

// GRAFICO 4 - MEDIOS DE PAGO

function pintarGrafico4() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_mediosPago_proceso.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);


            ctxLine = document.getElementById('grafico4').getContext('2d');

            labels = llaves;

            data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Dataset 1',
                        data: valores,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                        ],
                    },

                ]
            };

            config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                },
            };

            const myChart4 = new Chart(ctxLine, config);
        }
    });
}

// GRAFICO 5 - COLEGIOS

function pintarGrafico5() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_colegios_proceso.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);


            ctxLine = document.getElementById('grafico5').getContext('2d');

            data = {
                labels: llaves,
                datasets: [
                    {
                        label: 'Postulantes',
                        backgroundColor: generarColoresAleatorios(labels.length),
                        borderColor: generarColoresAleatorios(labels.length),
                        borderWidth: 1,
                        data: valores
                    }
                ]
            };

            config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        // title: {
                        //     display: true,
                        //     text: 'Chart.js Bar Chart'
                        // }
                    }
                },
            };

            const myChart5 = new Chart(ctxLine, config);
        }
    });
}

// GRAFICO 7 - GENERO

function pintarGrafico7() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_genero_proceso.php",
        success: function (r) {

            ctxLine = document.getElementById('grafico7').getContext('2d');


            data = {
                labels: [''],
                datasets: [
                    {
                        label: 'Femenino',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: [r.Femenino]
                    },
                    {
                        label: 'Masculino   ',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        data: [r.Masculino]
                    }
                ]
            };

            config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        // title: {
                        //     display: true,
                        //     text: 'Chart.js Bar Chart'
                        // }
                    }
                },
            };


            const myChart7 = new Chart(ctxLine, config);
        }
    });
}

// GRAFICO 8 -EDADES

function pintarGrafico8() {
    $.ajax({
        type: "POST",
        data:"id_proceso="+ id,
        url: "operaciones/estadisticas/obtener_postulantes_edades_proceso.php",
        success: function (r) {

            let llaves = Object.keys(r);
            let valores = Object.values(r).map(Number);

            ctxLine = document.getElementById('grafico8').getContext('2d');

            data = {
                labels: llaves,
                datasets: [
                    {
                        label: 'Postulantes',
                        data: valores,
                        stepped: 'before', // Puedes ajustar este valor según tus necesidades
                        borderColor: 'rgba(255, 99, 132, 0.5)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    },
                    // Puedes agregar más datasets si lo necesitas
                ],
            };

            config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        axis: 'x'
                    },
                    plugins: {
                        // title: {
                        //     display: true,
                        //     text: (ctx) => 'Step ' + ctx.chart.data.datasets[0].stepped + ' Interpolation',
                        // }
                    }
                }
            };

            const myChart8 = new Chart(ctxLine, config);

        }
    });
}