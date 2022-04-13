<?php
	header("Content-Type: application/xls");    
	header("Content-Disposition: attachment; filename=documento_exportado_" . date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache"); 
	header("Expires: 0");

	$conn = mysqli_connect('localhost', 'root', '', 'pos');
	
	if(!$conn){
		die("Error: Fallo al conectar con la base de datos");
	}
	
	$output = "";
	
	if(ISSET($_POST['export'])){
		$output .="
			<table>
				<thead>
					<tr>
						<th>Categoria</th>
						<th>Codigo</th>
						<th>Descripcion</th>
						<th>Stock</th>
						<th>Precio compra</th>
						<th>Precio ventas</th>
						<th>Cantidad de Ventas</th>
					</tr>
				<tbody>
		";
		
		$query = mysqli_query($conn, "SELECT * FROM productos inner join categorias on productos.id_categoria=categorias.id order by categoria") or die(mysqli_errno());
		while($fetch = mysqli_fetch_array($query)){
			
		$output .= "
					<tr>
						<td>".$fetch['categoria']."</td>
						<td>".$fetch['codigo']."</td>
						<td>".$fetch['descripcion']."</td>
						<td>".$fetch['stock']."</td>
						<td>".$fetch['precio_compra']."</td>
						<td>".$fetch['precio_venta']."</td>
						<td>".$fetch['ventas']."</td>
					</tr>
		";
		}
		
		$output .="
				</tbody>
				
			</table>
		";
		
		echo $output;
	}
	
?>