<?php
  include "../include/conexion.php";
  include "../include/busquedas.php";
?>
<!DOCTYPE html>
<html lang="es">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Caja <?php include ("../include/header_title.php"); ?></title>
    <!--icono en el titulo-->
    <link rel="shortcut icon" href="../img/favicon.ico">

    <!-- Google Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body>

    <section class="bg-white h-screen ">
        <div class="grid grid-cols-1 lg:grid-cols-2">
          <div class="lg:flex relative h-screen fixed top-0 left-0 items-end px-4 pb-10 pt-60 sm:pb-16 md:justify-center lg:pb-24 bg-gray-50 sm:px-6 lg:px-8">
            <div class="absolute inset-0">
              <img
                class="object-cover w-full"
                style="height: 100vh"
                src="https://images.pexels.com/photos/2325717/pexels-photo-2325717.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                alt=""
              />
            </div>
                  <?php
                    $buscar = buscarDatosSistema($conexion);
                    $res = mysqli_fetch_array($buscar);
                  ?>
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
      
            <div class="relative">
              <div class="w-full max-w-xl xl:w-full xl:mx-auto xl:pr-24 xl:max-w-xl">
                <h3 class="text-4xl font-bold text-white">
                  En nuestra intitución <?php echo $res['titulo']; ?> <br class="hidden xl:block" />valoramos su interés
                </h3>
              </div>
            </div>
          </div>
      
          <div class="flex items-center justify-center bg-white sm:px-1 sm:py-1 " >
            
                <form class="mt-1" action="operaciones/registrar_empresa.php" method="post" enctype="multipart/form-data">
                    <div class="max-w-2xl mx-auto text-center">
                        <h2 class="text-3xl font-bold leading-tight text-black sm:text-4xl lg:text-3xl">Solicita una cuenta de bolsa laboral</h2>
                    </div>
                            <div class="px-4 mx-auto sm:px-1 lg:px-1">                          
                                <div class="relative max-w-md mx-auto mt-8 md:mt-1">
                                    <div class="overflow-hidden bg-white rounded-md shadow-md">
                                        <div class="px-4 py-6 sm:px-8 sm:py-7">
                                            <form action="#" method="POST">
                                                <div class="space-y-2">
                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Nombre de la empresa *</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <input
                                                                type="text"
                                                                name="nombre_empresa"
                                                                placeholder="Razón social de la empresa"
                                                                id=""
                                                                onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                required
                                                                class="block w-full py-1 pl-4 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> RUC *</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <input
                                                                type="text"
                                                                name="ruc"
                                                                placeholder="N° de RUC"
                                                                required
                                                                id=""
                                                                oninput="validateInputNum(this,11)" 
                                                                class="block w-full py-1 pl-4 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Ciudad *</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <input
                                                                type="text"
                                                                name="ubicacion"
                                                                placeholder="Locación de la empresa"
                                                                required
                                                                id=""
                                                                onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                class="block w-full py-1 pl-4 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>
                          
                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Correo Electrónico *</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                                </svg>
                                                            </div>
                          
                                                            <input
                                                                type="email"
                                                                name="correo"
                                                                placeholder="Correo de la institución"
                                                                required
                                                                id=""
                                                                class="block w-full py-1 pl-10 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Teléfono / Celular *</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            </div>
                          
                                                            <input
                                                                type="text"
                                                                name="celular"
                                                                placeholder="N° telefónico o celular"
                                                                required
                                                                id=""
                                                                oninput="validateInputNum(this,9)" 
                                                                onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                class="block w-full py-1 pl-10 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Nombre del contacto * </label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            </div>
                          
                                                            <input
                                                                type="text"
                                                                name="contacto"
                                                                required
                                                                id=""
                                                                placeholder="Apellidos y nombres"
                                                                onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                class="block w-full py-1 pl-10 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>
                          
                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Cargo del contacto *</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            </div>
                          
                                                            <input
                                                                type="text"
                                                                name="cargo"
                                                                required
                                                                id="Cargo en la empresa"
                                                                placeholder="Cargo que desempeña"
                                                                onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                class="block w-full py-1 pl-10 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="" class="text-base font-medium text-gray-900"> Logo con el nombre de la empresa (opcional)</label>
                                                        <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                                            <input
                                                                type="file"
                                                                name="logo"
                                                                id=""
                                                                placeholder=""
                                                                accept="{.png, .jpg, .ico}"
                                                                class="block w-full py-1 pl-4 pr-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div>
                                                            <input
                                                                type="checkbox"
                                                                name="terminos"
                                                                required
                                                                id="terminos"
                                                            />
                                                            <label>Aceptar <a class="underline" href="<?php echo $res['req_bolsa_laboral']; ?>" target="_blank" >términos y condiciones</a></label>
                                                    </div>
        
                          
                                                    <div>
                                                        <button type="submit" class="inline-flex items-center justify-center w-full px-2 py-4 text-base font-semibold text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-md focus:outline-none hover:bg-blue-700 focus:bg-blue-700">
                                                          Solicitar una cuenta
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                         
                  </form>
          </div>
        </div>
      </section>
      
    <script>
      function validateInputNum(input, tamanio) {
          // Obtén el valor actual del campo de entrada
          let inputValue = input.value;

          // Remueve cualquier carácter no permitido (en este caso, letras)
          inputValue = inputValue.replace(/[^0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/g, '');
          inputValue = inputValue.slice(0, tamanio);
          // Actualiza el valor del campo de entrada
          input.value = inputValue.toUpperCase();
      }
    </script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/c09f996d50.js" crossorigin="anonymous"></script>
  </body>
</html>
