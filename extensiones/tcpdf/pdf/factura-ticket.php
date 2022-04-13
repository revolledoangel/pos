<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura{

public $codigo;

public function traerImpresionFactura(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$itemVenta = "codigo";
$valorVenta = $this->codigo;

$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$neto = number_format($respuestaVenta["neto"],2);
$impuesto = number_format($respuestaVenta["impuesto"],2);
$total = number_format($respuestaVenta["total"],2);

//TRAEMOS LA INFORMACIÓN DEL CLIENTE

$itemCliente = "id";
$valorCliente = $respuestaVenta["id_cliente"];

$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

$itemVendedor = "id";
$valorVendedor = $respuestaVenta["id_vendedor"];

$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set font
$pdf->SetFont('dejavusans', '', 8);
$pdf->AddPage('P', 'A7');
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->Cell(0, 0, 'TIENDAS PARURO', 1, 1, 'C');
$pdf->Cell(0, 6, 'Av. Lima 1020 José Galvez V.M.T.', 0, 0, 'C');
//---------------------------------------------------------

$bloque1 = <<<EOF

<br><br>cel: 921 852 098
<br>fecha: $fecha
<br>Ticket N°$valorVenta
<br>Vendedor: $respuestaVendedor[nombre]<br><br>

    <table>
        <tr>
			<th class="producto"><b>Producto</b></th>
			<th class="cantidad"><b>Cantidad</b></th>
            <th class="precio"><b>Precio</b></th>
        </tr>
    </table>
<hr size=10>
<br>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

// ---------------------------------------------------------


foreach ($productos as $key => $item) {

$valorUnitario = number_format($item["precio"], 2);

$precioTotal = number_format($item["total"], 2);

$bloque2 = <<<EOF

        <table>
            <tr>
                <td class="cantidad">$item[descripcion] </td>
                <td class="producto">  $item[cantidad] </td>
                <td class="precio">S/ $precioTotal</td>
            </tr>
                  
        </table>
	
EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');

}

// ---------------------------------------------------------

$bloque3 = <<<EOF

<hr size=10>
<br>

<table>
        <tr>
			<th></th>
			<th><b>Subtotal:</b></th>
            <th>S/ $neto</th>
        </tr>
		<tr>
			<th></th>
			<th><b>Dscto:</b></th>
            <th>S/ $impuesto</th>
        </tr>
		<tr>
			<th></th>
			<th><b>Total:</b></th>
            <th>S/ $total</th>
        </tr>

</table>



<p><small>Nota: Para realizar el cambio o devolución dispones de 2 días con el artículo y empaque en perfecto estado y la boleta de compra</small></p>
<p>¡GRACIAS POR SU COMPRA!</p>
<br><br>
<hr size=10>
<br><br>
EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');



// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 

//$pdf->Output('factura.pdf', 'D');
$pdf->Output('factura.pdf');

}

}

$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();

?>