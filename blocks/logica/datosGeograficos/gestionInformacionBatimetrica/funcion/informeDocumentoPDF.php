<?php
$ruta = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" );
include ($ruta . "/plugin/html2pdf/html2pdf.class.php");
class ProcesarInforme {
	var $miConfigurador;
	var $miInspectorHTML;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	var $conexion;
	function __construct($lenguaje, $sql) {
		$this->miInspectorHTML = \InspectorHTML::singleton ();
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
	}
	function contenidoInforme() {
		
		/* Conexión FrameWork */
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		/*
		 * Consultar Usuario
		 */
		$cadenaSql = $this->miSql->getCadenaSql ( "consultar_datos_usuario", $_REQUEST ['usuario'] );
		$usuario = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$usuario=$usuario[0];
		
		
		/* Conexión */
		$conexion = "logica";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		/*
		 * Consultar Recomendaciones
		 */
		$cadenaSql = $this->miSql->getCadenaSql ( "consultar_recomedaciones", $_REQUEST ['id_zona'] );
		$recomendaciones = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		/*
		 * Consultar Recomendaciones
		 */
		$cadenaSql = $this->miSql->getCadenaSql ( "consultar_informacion_zona_estudio", $_REQUEST ['id_zona'] );
		$info_zona = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$info_zona = $info_zona [0];
		

		
		$directorio = $this->miConfigurador->getVariableConfiguracion ( 'rutaUrlBloque' );

		
		$contenidoPagina = "
				<style type=\"text/css\">
				    table {
				        color:#333; /* Lighten up font color */
				        font-family:Helvetica, Arial, sans-serif; /* Nicer font */
	                    border-collapse:collapse;
				        border-spacing: 3px;
						/*background: #ECECEC;*/
				    }
				    td, th {
				        border: 1px solid #888;
				        height: 13px;
				    } /* Make cells a bit taller */
					col{
					width=50%;
						
					}
						
				    th {
				        
				        font-weight: bold; /* Make sure they're bold */
				        text-align: center;
				        font-size:10px
				    }
				    td {
				        	
				        text-align: left;
				        font-size:10px
				    }
				</style>
		
		
<page backtop='5mm' backbottom='5mm' backleft='5mm' backright= '5mm' footer='page'>
		
        <table align='left' style='width:100%;' >
            <tr>
                <td align='center' style='width:40%;' >
                    <img src='" . $directorio . "/css/logo_informe/Logo_Dimar.png'  width='250' height='100'>
                </td>
                <td align='center' style='width:60%;' >
                    <font size='9px'><b>DIRECCIÓN GENERAL MARÍTIMA</b></font>
                    <br>
                    <font size='7px'><b>Autoridad Marítima Colombiana</b></font>		
                    <br>		
                    <font size='7px'><b>NIT: 30.027.904-1</b></font>
                     <br>
                    <font size='3px'>Carrera 54 No 26-50 CAN - Bogotá D.C., Colombia.TELEFONO (571) 220 0490 </font>
                     <br>
                    <font size='5px'>www.dimar.mil.co</font>
                     <br>
                    <font size='4px'>" . date ( "Y-m-d" ) . "</font>
                </td>
            </tr>
        </table>
                    		
      <table  style='width:100%;' >
            <tr>
                <td align='center' style='width:100%;border=none;'  >
                    <br>		
                    <br>		
                    <font size='9px'><b>FORMATO ANÁLISIS DE RIESGO A LA NAVEGACIÓN</b></font>
                    <br>
                 </td>
            </tr>
        </table>
      <br>
        <table align='center' style='width:70%;' >
            <tr>
                <td style='width:30%;border: 1px solid #000; text-align=center;'><b>Riesgo</b></td>
                <td style='width:35%;border: 1px solid #000; text-align=center;'><b>Acción</b></td>
                <td style='width:35%;border: 1px solid #000; text-align=center;'><b>Descripción</b></td>
            </tr>
            <tr>
                <td style='width:30%; background: #73D667;border: 1px solid #000; text-align=center;'><b>1 a 2</b></td>
                <td style='width:35%; border: 1px solid #000; text-align=center;'>Monitoreo</td>
                <td style='width:35%; border: 1px solid #000; text-align=center;'>Nivel de Riesgo Aceptable</td>
            </tr>
            <tr>
                <td style='width:30%; background: #FFAE68;border: 1px solid #000; text-align=center;'><b>3 a 4</b></td>
                <td style='width:35%; border: 1px solid #000;text-align=center;'>Especificar la Acción</td>
                <td style='width:35%; border: 1px solid #000; text-align=center;'>Nivel de Riesgo Aceptable con Precaución</td>
            </tr>        		
            <tr>
                <td align='center' style='width:30%; background: #FF4040;border: 1px solid #000;text-align=center;'><b>6 a 9</b></td>
                <td align='center' style='width:35%; border: 1px solid #000; text-align=center;'>Medidas de Emergencia </td>
                <td align='center' style='width:35%; border: 1px solid #000; text-align=center;'>Nivel de Riesgo Inaceptable</td>
            </tr>        		
        </table>            		
      <br>
      <table align='left' style='width:100%;' >
            <tr>
                <td align='left' style='width:10%;'>Región : </td>
                <td align='center' style='width:40%;'><b>" . $info_zona ['region'] . "</b></td>
                <td align='left' style='width:10%;'>Zona : </td>
                <td align='center' style='width:40%;'><b>" . $info_zona ['sector'] . "</b></td>
            </tr>
            <tr>
                <td align='left' style='width:10%;'>Título : </td>
                <td align='center' style='width:40%;'><b>" . $_REQUEST ['titulo_proyecto'] . "</b></td>                     		
                <td align='left' style='width:10%;'>Total AtoN : </td>
                <td align='center' style='width:40%;'><b>" . $info_zona ['total_aton'] . "</b></td>    		
            </tr>
        </table>
       <br>
   			<table style='width:100%;'>
			<tr> 
			<td style='width:20%;text-align=center;'><b>Riesgo</b></td>
			<td style='width:40%;text-align=center;'><b>Acciones Preventivas</b></td>
			<td style='width:40%;text-align=center;'><b>Señalización</b></td>
			</tr>";
		
		foreach ( $recomendaciones as $valor ) {
			
			$contenido_riesgo = explode ( ",", $valor ['riesgo'] );
			
			$contenidoPagina .= "<tr>
                    			<td style='width:20%;text-align=center;'>" . $contenido_riesgo [1] . "</td>
                    			<td style='width:40%;text-align=justify;'><font size='0.5px'>" . $valor ['acciones_prv'] . "</font></td>
                    			<td style='width:40%;text-align=justify;'><font size='0.5px'>" . $valor ['senalizacion_ext'] . "</font></td>
                    		    </tr>";
		}
		
		$contenidoPagina .= "</table>
						</page> 		
						<page_footer>
							<table style='width:100%;'>
								<tr>
								<td style='width:15%;text-align:left;'>Elaborado por: </td>
								<td style='width:35%;text-align:center;'>".$usuario['nombre_usuario']."</td>
								<td style='width:15%;text-align:left;'>Cargo:</td>
								<td style='width:35%;text-align:center;'>PROFESIONAL SEÑALIZACIÓN MARÍTIMA</td>
								</tr>
							</table>
						    <br>
							<table style='width:100%;'>
								<tr>
								<td style='width:15%;text-align:left;'>Revisó: </td>
								<td style='width:35%;text-align:center;'> </td>
								<td style='width:15%;text-align:left;'>Aprobó: </td>
								<td style='width:35%;text-align:center;'> </td>
								</tr>
								<tr>
								<td style='width:15%;text-align:left;'>Fecha Revisión: </td>
								<td style='width:35%;text-align:centes;'> </td>
								<td style='width:15%;text-align:left;'>Fecha Aprobación: </td>
								<td style='width:35%;text-align:center;'> </td>
								</tr>
							</table>
						</page_footer>";
	
		return $contenidoPagina;
		
		// echo $contenidoPagina;
		// exit;
	}
}

$miProcesador = new ProcesarInforme ( $this->lenguaje, $this->sql );
$contenido = $miProcesador->contenidoInforme ();

ob_start ();

$objetoPDF = new HTML2PDF ( 'P', 'LETTER', 'es', true, 'UTF-8' );
// HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8');
$objetoPDF->pdf->SetDisplayMode ( 'fullpage' );
$objetoPDF->writeHTML ( $contenido );
$objetoPDF->Output ( "Resultados_" . utf8_encode($_REQUEST['titulo_proyecto']) . "_" . date ( 'Y-m-d' ) . ".pdf", "D" );

?>