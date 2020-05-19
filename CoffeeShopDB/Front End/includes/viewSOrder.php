<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("library.php");
if (isset($_GET['sID']) &&
	isset($_GET['eID'])) {
	$sordID = $_GET['sID'];
	$empID = $_GET['eID'];
    $dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
    $dbName = "CoffeeShopDB";                                                                                                       
    $dbUser = "coffeeshop";
    $dbPass = "cmps3420";
    $connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
    if ($connection) {
        $sql = "select * from receiptInfo where sorderid = $sordID";
    	if (!pg_query($connection, $sql)) {
			  echo "ERROR FILLING ORDER";
      } else {
			require_once __DIR__ . '/../mpdf/autoload.php';
			$mpdf = new \Mpdf\Mpdf();
			$stylesheet = file_get_contents('../../pdf_test/style2.css');
			$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
      $query = pg_query($connection,$sql);
      $row = pg_fetch_array($query);
      $unit_cost = number_format($row[6]*5, 2, '.', '');
      $total_cost = number_format($row[6]*5*$row[2], 2, '.', '');
      $date = date("m-d-Y", strtotime($row[10]));
      /*$time_rec = date("h:i:sa", strtotime($row[10]));*/
      $time_del = date("h:i:sa", strtotime($row[11]));
      $discount = $row[15];
      $discount_view = $row[15]*100;
			$html = '
    		<header class="clearfix">
      			<div style="text-align: center; margin-bottom: 10px">
        			<img src="../images/logo.png" style="width:200px">
      			</div>
      			<h1>Receipt For '.$row[1].'</h1>
      			<div id="company" class="clearfix">
        			<div>Coffee Shop '.$row[12].'</div>
        			<div>'.$row[13].'</div>
        			<div>'.$row[14].'</div>
        			<!--<div><a href="mailto:company@example.com">company@example.com</a></div>-->
      			</div>
      			<div id="project">
        			<div><span>CASHIER</span> '.$row[7].'</div>
        			<div><span>BARISTA</span> '.$row[8].'</div>
        			<div><span>DATE</span> '.$date.'</div>
        			<!--<div><span>TIME ORDERED</span> '.$time_rec.'</div>-->
        			<div><span>TIME DELIVERED</span> '.$time_del.'</div>
      			</div>
    		</header>
    		<main>
      			<table>
        			<thead>
          				<tr>
            				<th class="service">PRODUCT</th>
            				<th class="desc">SPECIAL INSTRUCTIONS</th>
            				<th>PRICE</th>
            				<th>QTY</th>
            				<th>TOTAL</th>
          				</tr>
        			</thead>
        			<tbody>
                <tr>
                  <td class="service">'.$row[5].' '.$row[4].'</td>
                  <td class="desc">'.$row[3].'</td>
                  <td class="unit">$'.$unit_cost.'</td>
                  <td class="qty">'.$row[2].'</td>
                  <td class="total">$'.$total_cost.'</td>
                </tr>
';
        $sum = $total_cost;
	      $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        while ($row = pg_fetch_array($query)) {
          $unit_cost = number_format($row[6]*5, 2, '.', '');
          $total_cost = number_format($row[6]*5*$row[2], 2, '.', '');
          $html2 = '
          <tr>
            <td class="service">'.$row[5].' '.$row[4].'</td>
            <td class="desc">'.$row[3].'</td>
            <td class="unit">$'.$unit_cost.'</td>
            <td class="qty">'.$row[2].'</td>
            <td class="total">$'.$total_cost.'</td>
          </tr>';
			    $mpdf->WriteHTML($html2,\Mpdf\HTMLParserMode::HTML_BODY);
          $sum += number_format($total_cost, 2, '.', '');
        }
        $sum *= (1-$discount);
        $total = number_format($sum + $sum*0.0725, 2, '.' , '');
        $tax = number_format($sum *0.0725, 2, '.', '');
        $html3 = '
          			<tr>
            			<td colspan="4">DISCOUNT</td>
            			<td class="total">'.$discount_view.'%</td>
          			</tr>
          			<tr>
            			<td colspan="4">SUBTOTAL</td>
            			<td class="total">$'.number_format($sum, 2, '.', '').'</td>
          			</tr>
          			<tr>
            			<td colspan="4">TAX 7.25%</td>
            			<td class="total">$'.$tax.'</td>
          			</tr>
          			<tr>
            			<td colspan="4" class="grand total">GRAND TOTAL</td>
            			<td class="grand total">$'.$total.'</td>
          			</tr>
        			</tbody>
      			</table>
      			<div id="notices">
        			<div>Enjoy Your Coffee!</div>
        			<div class="notice">Each cup of coffee or tea is fresh brewed with the finest ingredients just for you!</div>
      			</div>
    			</main>
    			<footer>
      			Receipt was created on a computer and is valid without the signature and seal.
    			</footer>
			';
			$mpdf->WriteHTML($html3,\Mpdf\HTMLParserMode::HTML_BODY);

			// Output a PDF file directly to the browser
			$mpdf->Output();
			header("Location: ../#home?t=3");
      }
    }
} else {
	header("Location: ../#home");
}
