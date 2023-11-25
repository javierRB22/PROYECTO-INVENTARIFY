<?php

include 'conexion_be.php';

$nombre_completo = $_POST ['nombre_completo'];
$correo = $_POST ['correo'];
$usuario = $_POST ['usuario'];
$contraseña = $_POST ['contraseña'];

//encriptamiento de contraseña 
//$contraseña = hash('sha512', $contraseña);

$query ="INSERT INTO usuarios(nombre_completo, correo, usuario, contraseña)
        VALUES ('$nombre_completo', '$correo', '$usuario', '$contraseña')";

             //verificacion si se repite correo
            $verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' ");

            if(mysqli_num_rows($verificar_correo) > 0 ){

                  echo'
                  <script>

               alert ("este correo ya estaado, registr intenta con otro diferente");
               window.location = "./index.php";
               
               </script>
                  
                  ';
                  exit();

                  
            }

         //verficacion si se repite usuario 
            $verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario' ");

            if(mysqli_num_rows($verificar_usuario) > 0 ){

                  echo'
                  <script>

               alert ("este usuarios ya esta registrado, intenta con otro diferente");
               window.location = "./index.php";
               
               </script>
                  
                  ';
                  exit();

                  
            }

         $ejecutar = mysqli_query($conexion, $query);

         if($ejecutar){
               echo'
               <script>

               alert ("usuario almacenado exitosamente");
               window.location = "./index.php";
               
               </script>
               
               ';

         }else {
            echo'
               <script>

               alert ("intentalo de nuevo, usuario no almacenado");
               window.location = "./index.php";
               
               </script>
               
               ';

         }

         mysqli_close($conexion);





?>