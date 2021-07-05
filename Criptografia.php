<?php



/*FALTAN LOS CIFRADOS DE:
    DESPLAZAMIENTO: PURO CON PALABRA CLAVE

    TRANSPOSICION: ZIG ZAG

    SUSTITUCION: POLI ALFABETICO


*/
function cifrado_puro(){
    $texto_claro = "UNACOSAESSABERYOTRASABERENSENAR";
    $texto_cifrado = "";
    $alfabeto = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","Ñ","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $tamano_alfabeto = 27;
    $desplazamiento = 19;

    for($i = 0; $i < strlen($texto_claro) ; $i++){
        $posicion = array_search($texto_claro[$i], $alfabeto);
        $posicion_caracter_cifrado = ($posicion + $desplazamiento) % $tamano_alfabeto;
        $texto_cifrado = $texto_cifrado . $alfabeto[$posicion_caracter_cifrado]; 
        
    }

    echo "Texto en claro: $texto_claro <br />";
    echo "Texto cifrado: $texto_cifrado <br />";
}

    //cifrado();


function cifrado_por_decimacion_pura(){
    $texto_claro = "HOLAATODOSCOMOESTAN";
    $texto_cifrado = "";
    $alfabeto = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","Ñ","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $tamano_alfabeto = 27;
    $decimacion = 4;

    for($i = 0; $i < strlen($texto_claro) ; $i++){
        $posicion = array_search($texto_claro[$i], $alfabeto);
        $posicion_caracter_cifrado = $decimacion * $posicion % $tamano_alfabeto;
        $texto_cifrado = $texto_cifrado . $alfabeto[$posicion_caracter_cifrado]; 
    }

    

    echo "Texto en claro: $texto_claro <br />";
    echo "Texto cifrado: $texto_cifrado <br />";
}

function cifrado_por_cifra_afin(){
    $texto_claro = "HOLAATODOSCOMOESTAN";
    $texto_cifrado = "";
    $alfabeto = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","Ñ","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $tamano_alfabeto = 27;
    $decimacion = 4;
    $desplazamiento = 2;

    for($i = 0; $i < strlen($texto_claro) ; $i++){
        $posicion = array_search($texto_claro[$i], $alfabeto);
        $posicion_caracter_cifrado = ($decimacion * $posicion + $desplazamiento) % $tamano_alfabeto;
        $texto_cifrado = $texto_cifrado . $alfabeto[$posicion_caracter_cifrado]; 
    }

    

    echo "Texto en claro: $texto_claro <br />";
    echo "Texto cifrado: $texto_cifrado <br />";
}


//Funciona para cifrado por fila y columna, cambiar nombre de la funcion si es necesario
function cifrado_transposcion_por_columnas($texto_en_claro, $cantidad_de_columnas, $caracter_para_rellenar){
    $texto_en_claro = formatear_texto($texto_en_claro, $cantidad_de_columnas, $caracter_para_rellenar);
    $texto_cifrado = "";    
    $matriz =  str_split($texto_en_claro, $cantidad_de_columnas);
    for($i = 0 ; $i < $cantidad_de_columnas; $i++){
        for($j = 0 ; $j < sizeof($matriz) ; $j++){
            $bloque = $matriz[$j];
            $texto_cifrado = $texto_cifrado . $bloque[$i];                                 
        }

    }
 
    return $texto_cifrado;
}


function cifrado_transposicion_por_grupo($texto_en_claro, $clave){
    $texto_cifrado = "";
    $clave_convertida = convertir_clave($clave);
    $cantidad_de_bloques = sizeof($clave_convertida);
    $texto_en_claro = formatear_texto($texto_en_claro, $cantidad_de_bloques, "X");
    $matriz =  str_split($texto_en_claro, $cantidad_de_bloques);

    for($i = 0; $i < count($matriz); $i++){
        $bloque = $matriz[$i];
        foreach($clave_convertida as $elemento){
            $texto_cifrado = $texto_cifrado . $bloque[intval($elemento)-1];
        }
    }
    return $texto_cifrado;
}

function cifrado_transposicion_por_series($texto_en_claro){
    $texto_en_claro = strtoupper($texto_en_claro);
    $texto_en_claro = quitar_espacios_en_blanco($texto_en_claro);

    $submensaje_1 = "";
    $submensaje_2 = "";
    $submensaje_3 = "";
    for($i = 0 ; $i < strlen($texto_en_claro) ; $i++){
        if(es_primo($i+1)){
            $submensaje_1 = $submensaje_1 . $texto_en_claro[$i];
        }else if(es_par($i+1)){
            $submensaje_2 = $submensaje_2 . $texto_en_claro[$i];
        }else{
            $submensaje_3 = $submensaje_3 . $texto_en_claro[$i];
        }
    }

    return $submensaje_1 . $submensaje_2 . $submensaje_3;;
}





/// FUNCIONES COMPLEMENTARIAS


//PASAR TEXTO A MAYUSCULAS, QUITAR ESPACIOS, Y RELLENAR LOS CARACTERES FALTANTES
function formatear_texto($texto_en_claro, $cantidad_de_columnas, $caracter_para_rellenar){
    $texto_en_claro = strtoupper($texto_en_claro);
    $texto_en_claro = quitar_espacios_en_blanco($texto_en_claro);
    $relleno = strlen($texto_en_claro) % $cantidad_de_columnas;
    if($relleno > 0){
        $texto_en_claro = rellenar_texto($texto_en_claro,$cantidad_de_columnas-$relleno,$caracter_para_rellenar);
    }

    return $texto_en_claro;
}

//CONVERTIR LA CLAVE A ARRAY PARA USARLO EN LA TRANSPOSICION POR GRUPOS
function convertir_clave($clave){
    $clave_str = strval($clave);
    $clave_array = str_split($clave_str);
    return $clave_array;
}

function quitar_espacios_en_blanco($texto){
    $longitud_del_texto = strlen($texto);
    $nuevo_texto = "";

    for($i = 0 ; $i < $longitud_del_texto ; $i++){
        if($texto[$i] != " "){
            $nuevo_texto = $nuevo_texto . $texto[$i];
        }
    }

    return $nuevo_texto;
}

function rellenar_texto($texto, $cantidad_a_rellenar, $caracter_para_rellenar){
    $caracter_para_rellenar = strtoupper($caracter_para_rellenar);
    for($i = 0 ; $i < $cantidad_a_rellenar; $i++){
        $texto = $texto . $caracter_para_rellenar;
    }
    return $texto;
}


function es_primo($valor){
    if ($valor == 1)  return false;
    for($i = 2; $i <= intdiv($valor, 2); $i++){
        if($valor % $i == 0) return false;
    }
    return true;
}

function es_par($valor){
    if($valor % 2 == 0) return true;
    return false;
 }
$texto = "AHORA CIFRAMOS POR SERIES";
echo cifrado_transposicion_por_series($texto);

?>