<?php
	include_once "common.php";
	$db = new SQLData();
	
	$msg="";
	if(isset($_SESSION['number'])!=null){
		if(isset($_POST['registering'])==1){
			$name = $_POST['name'];
			$birthplace = $_POST['place_of_birth'];
			$bod = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
			$gender = $_POST['gender'];
			$address = $_POST['address'];
			$rtrw = $_POST['rtrw'];
			$kelurahan = $_POST['kelurahankecamatan'];
			$kecamatan = $_POST['kelurahankecamatan'];
			$city = $_POST['city'];
			$province = $_POST['province'];
			$kodearea = $_POST['kode_area'];
			$mobilenumber = $_POST['mobile_number'];
			$religion = $_POST['religion'];
			$mothername = $_POST['mother_name'];
			$idnumber = $_POST['id_number'];
			$idtype = $_POST['id_card_type'];
			$npwp = $_POST['npwp'];
			$email = $_POST['email'];
			$contactperson = $_POST['contactable_person'];
			$contactrelation = $_POST['contactable_relation'];
			$contactaddress = $_POST['contactable_address'];
			$contactphone = $_POST['contactable_phone'];
			$packed = $_POST['packed'];
			$extra = $_POST['extra'];
			$payment = $_POST['payment'];
			$db->open(1);
			$sql = "INSERT INTO axis_customer_table
					VALUE (null,'{$name}',							
							'{$bod}',
							'{$birthplace}',
							'{$gender}',
							'{$address}',
							'{$rtrw}',
							'{$kelurahan}',
							'{$kecamatan}',
							'{$city}',
							'{$province}',
							{$kodearea},
							'{$mobilenumber}',
							'{$religion}',
							'{$mothername}',
							'{$idnumber}',
							'{$idtype}',
							'{$npwp}',
							'{$email}',
							'{$contactperson}',
							'{$contactrelation}',
							'{$contactaddress}',
							'{$contactphone}',
							'{$packed}',
							'{$extra}',
							'{$payment}',0)";
			var_dump($sql);
			$rs = $db->query($sql);	
			echo mysql_error();
			$db->close();
			
			if($rs){
				$db->open(1);
					$sqll="UPDATE axis_registered_number
							SET n_status=1
							WHERE number=".$_SESSION['number'];
					$rss=$db->query($sqll);
				$db->close();
				if($rss){
					$msg="Your registration is completed.";
					sendRedirect('index.php');
				}
			}else{
				$msg="Your registration is failed. Please complete the form.";
			}
		}
	}else{
		sendRedirect('index.php');
	}
?>
<html>
<head>
</head>
<body>
<div style="padding:20px;padding-right:600px">
	<div style="padding:20px;" id="white">
		<h3><?php echo $msg; ?></h3>
		<form action="#" method="post" id="PostpaidRegistratorAddForm">
			<table>
			<tr><th align="left"><h1>PERSONAL DATA </h1></th></tr>
			<tr>
				<td width="500"><h2>Full Name (written in KTP/SIM/Paspor)</h2></td>
				<td> <input type="text" name="name"  class="inputBox"></td>
			</tr>
			<tr>
				<td><h2>Gender </h2></td>
				<td>
					<select id="PostpaidRegistratorGender" class="text" name="gender">
					<option value="">-Choose-</option>
					<option value="male">Male</option>
					<option value="female">Female</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><h2>Date of Birth </h2></td>
				<td>Date&nbsp;
					<select id="PostpaidRegistratorDate" class="date" name="date">
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>&nbsp;	
					Month&nbsp;					
					<select id="PostpaidRegistratorMonth" class="month" name="month">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>&nbsp;	
					Year&nbsp;	
					<select id="PostpaidRegistratorYear" class="year" name="year">
						<option value="1932">1932</option>
						<option value="1933">1933</option>
						<option value="1934">1934</option>
						<option value="1935">1935</option>
						<option value="1936">1936</option>
						<option value="1937">1937</option>
						<option value="1938">1938</option>
						<option value="1939">1939</option>
						<option value="1940">1940</option>
						<option value="1941">1941</option>
						<option value="1942">1942</option>
						<option value="1943">1943</option>
						<option value="1944">1944</option>
						<option value="1945">1945</option>
						<option value="1946">1946</option>
						<option value="1947">1947</option>
						<option value="1948">1948</option>
						<option value="1949">1949</option>
						<option value="1950">1950</option>
						<option value="1951">1951</option>
						<option value="1952">1952</option>
						<option value="1953">1953</option>
						<option value="1954">1954</option>
						<option value="1955">1955</option>
						<option value="1956">1956</option>
						<option value="1957">1957</option>
						<option value="1958">1958</option>
						<option value="1959">1959</option>
						<option value="1960">1960</option>
						<option value="1961">1961</option>
						<option value="1962">1962</option>
						<option value="1963">1963</option>
						<option value="1964">1964</option>
						<option value="1965">1965</option>
						<option value="1966">1966</option>
						<option value="1967">1967</option>
						<option value="1968">1968</option>
						<option value="1969">1969</option>
						<option value="1970">1970</option>
						<option value="1971">1971</option>
						<option value="1972">1972</option>
						<option value="1973">1973</option>
						<option value="1974">1974</option>
						<option value="1975">1975</option>
						<option value="1976">1976</option>
						<option value="1977">1977</option>
						<option value="1978">1978</option>
						<option value="1979">1979</option>
						<option value="1980">1980</option>
						<option value="1981">1981</option>
						<option value="1982">1982</option>
						<option value="1983">1983</option>
						<option value="1984">1984</option>
						<option value="1985">1985</option>
						<option value="1986">1986</option>
						<option value="1987">1987</option>
						<option value="1988">1988</option>
						<option value="1989">1989</option>
						<option value="1990">1990</option>
						<option value="1991">1991</option>
						<option value="1992">1992</option>
						<option value="1993">1993</option>
						<option value="1994">1994</option>
						<option value="1995">1995</option>
						<option value="1996">1996</option>
						<option value="1997">1997</option>
						<option value="1998">1998</option>
						<option value="1999">1999</option>
						<option value="2000">2000</option>
						<option value="2001">2001</option>
						<option value="2002">2002</option>
						<option value="2003">2003</option>
						<option value="2004">2004</option>
						<option value="2005">2005</option>
						<option value="2006">2006</option>
						<option value="2007">2007</option>
						<option value="2008">2008</option>
						<option value="2009">2009</option>
						<option value="2010">2010</option>
						<option value="2011">2011</option>
						<option value="2012">2012</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><h2>Place of Birth </h2></td>
				<td><input type="text" id="PostpaidRegistratorPlaceOfBirth" maxlength="50" class="text" name="place_of_birth"/></td>
			</tr>
			<tr>
				<td><h2>Address </h2></td>
				<td><textarea id="PostpaidRegistratorAddress" cols="34" rows="4" rap="no" class="text" name="address"></textarea></td>
			</tr>
			<tr>
				<td><h2>RT/ RW</h2></td>
				<td><input type="text" id="PostpaidRegistratorRtrw" maxlength="20" class="text" name="rtrw"/></td>
			</tr>
			<tr>
				<td><h2>Kelurahan/ Kecamatan</h2></td>
				<td><input type="text" id="PostpaidRegistratorKelurahankecamatan" maxlength="50" class="text" name="kelurahankecamatan"/></td>
			</tr>
			<tr>
				<td><h2>City</h2></td>
				<td><input type="text" id="PostpaidRegistratorCity" maxlength="50" class="text" name="city"/></td>
			</tr>
			<tr>
				<td><h2>Province</h2></td>
				<td>
					<select id="PostpaidRegistratorProvince" class="text" name="province">
					<option value="">-Choose-</option>
					<option value="Jabodetabek">Jabodetabek</option>
					<option value="Central Java">Central Java</option>
					<option value="East Java">East Java</option>
					<option value="West Java">West Java</option>
					<option value="Bali Lombok">Bali Lombok</option>
					<option value="North Sumatera">North Sumatera</option>
					<option value="West Sumatera">West Sumatera</option>
					<option value="Riau dan Kepulauan Riau">Riau dan Kepulauan Riau</option>
					<option value="Lainnya">Lainnya</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><h2>Phone</h2></td>
				<td><input type="text" id="PostpaidRegistratorKodeArea" maxlength="11" class="tlparea" name="kode_area" size="5"/> &nbsp;<input type="text" id="PostpaidRegistratorPhone" maxlength="50" class="date month" name="phone"/></td>
			</tr>
			<tr>
				<td><h2>Mobile Number</h2></td>
				<td>
					<input type="text" id="PostpaidRegistratorMobileNumber" maxlength="50" class="text" name="mobile_number" value="<?php echo $_SESSION['number'];?>" disabled="disabled" />
					<input type="hidden" id="PostpaidRegistratorMobileNumber" maxlength="50" class="text" name="mobile_number" value="<?php echo $_SESSION['number'];?>"/>
				</td>
			</tr>
			<tr>
				<td><h2>Religion</h2></td>
				<td><input type="text" id="PostpaidRegistratorReligion" maxlength="255" class="text" name="religion"/></td>
			</tr>
			<tr>
				<td><h2>Mother's maiden name</h2></td>
				<td><input type="text" id="PostpaidRegistratorMotherName" maxlength="255" class="text" name="mother_name"/></td>
			</tr>
			<tr>
				<td><h2>ID No.</h2></td>
				<td><input type="text" id="PostpaidRegistratorIdNumber" maxlength="50" class="text" name="id_number"/></td>
			</tr>
			<tr>
				<td><h2>Type of ID</h2></td>
				<td>
					<select id="PostpaidRegistratorIdCardType" class="text" name="id_card_type">
					<option value="">-Choose-</option>
					<option value="KTP/KITAS">KTP/KITAS</option>
					<option value="SIM">SIM</option>
					<option value="PASSPORT">PASSPORT</option>
					<option value="KARTU PELAJAR">KARTU PELAJAR</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><h2>Tax ID No.</h2></td>
				<td><input type="text" id="PostpaidRegistratorNpwp" maxlength="255" class="text" name="npwp"/></td>
			</tr><tr>
				<td><h2>Email</h2></td>
				<td><input type="text" id="PostpaidRegistratorEmail" maxlength="50" class="text" name="email"/></td>
			</tr>
			<tr><th colspan="2"><hr width="800"></th></tr>
			<tr><th align="left"><h1>Orang terdekat</h1></th></tr>
			<tr>
				<td><h2>Name</h2></td>
				<td><input type="text" id="PostpaidRegistratorContactablePerson" maxlength="50" class="text" name="contactable_person"/></td>
			</tr>
			<tr>
				<td><h2>Relations</h2></td>
				<td>
					<select id="PostpaidRegistratorContactableRelation" class="text" name="contactable_relation">
					<option value="">-Choose-</option>
					<option value="Anak">Anak</option>
					<option value="Orang tua">Orang tua</option>
					<option value="Saudara kandung">Saudara kandung</option>
					<option value="Lainnya">Lainnya</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><h2>Address</h2></td>
				<td><textarea id="PostpaidRegistratorContactableAddress" maxlength="50" cols="34" rows="4" wrap="yes" class="text" name="contactable_address"/></textarea></td>
			</tr>
			<tr>
				<td><h2>Phone</h2></td>
				<td><input type="text" id="PostpaidRegistratorContactablePhone" maxlength="50" class="text" name="contactable_phone"/></td>
			</tr>
			<tr><th colspan="2"><hr width="800"></th></tr>
			<tr><th align="left"><h1>Additional Package</h1></th></tr>
			<tr>
				<td><h2>Monthly Basic Package</h2></td>
				<td>
					<input type="radio" value="50000" class="radiovalue" id="PostpaidRegistratorPacked50000" name="packed"/>
					<span class="radiovalue">50.000</span>
					<input type="radio" value="100000" class="radiovalue" id="PostpaidRegistratorPacked100000" name="packed"/>
					<span class="radiovalue">100.000</span>
					<input type="radio" value="150000" class="radiovalue" id="PostpaidRegistratorPacked150000" name="packed"/>
					<span class="radiovalue">150.000</span>
				</td>
			</tr>
			<tr>
				<td><h2>Additional Package Options</h2></td>
				<td>
					<input type="checkbox" id="PostpaidRegistratorExtraPackedInternet" value="Internet" name="extra"><label for="PostpaidRegistratorExtraPackedInternet">Internet</label></br>
					<input type="checkbox" id="PostpaidRegistratorExtraPackedSMS" value="SMS" name="extra"><label for="PostpaidRegistratorExtraPackedSMS">SMS</label></br>
					<input type="checkbox" id="PostpaidRegistratorExtraPackedBlackBerry" value="BlackBerry" name="extra"><label for="PostpaidRegistratorExtraPackedBlackBerry">BlackBerry</label>
				</td>
			</tr>
			<tr><th colspan="2"><hr width="800"></th></tr>
			<tr>
				<td><h2>Payment</h2></td>
				<td>
					<input type="radio" value="Deposit" class="radiovalue" id="PostpaidRegistratorPaymentDeposit" name="payment"/>
					<span class="radiovalue">Deposit</span><br>
					<input type="radio" value="Auto debit kartu kredit" class="radiovalue" id="PostpaidRegistratorPaymentAutoDebitKartuKredit" name="payment"/>
					<span class="radiovalue">Auto debit kartu kredit</span></br>
					<input type="radio" value="Cash/Transfer" class="radiovalue" id="PostpaidRegistratorPaymentCashTransfer" name="payment"/>
					<span class="radiovalue">Cash/Transfer</span><br>
				</td>
			</tr>
			<tr><th colspan="2"><hr width="800"></th></tr>
			<tr><th><h1>Informasi tagihan / pembayaran</h1></tr>
			<tr><td colspan="2"><li>Billing will be sent via SMS every month</li></td></tr>
			<tr><td colspan="2"><li>You may also check your usage anytime by calling *100#</li></td></tr>
			<tr><td colspan="2"><li>Alternatively, it will be sent to your Email address</li></td></tr>			
			<tr><th colspan="2"><hr width="800"></th></tr>
			<tr>
				<td>
				Hereby I Declare, Acknowledge and agree that:
					<ol>
						<li>All of the personal information provided are accurate and accountable.</li>
						<li>PT. Natrindo Telepon Selular has provided and given all information related to AXIS Post Paid Services.</li>
						<li>By signing this application form, it shall mean that:
							<ul>
								<li>I have agree to accept the effectiveness of the terms and conditions which can be modified partially at anytime by PT Natrindo Telepon Seluler.</li>
								<li>I agree to be fully responsible of any fee and burden incurred related to AXIS Post Paid Services.</li>
							</ul>
						</li>
						<li>AXIS shall fully be entitled to approve or decline this application without any obligation to give any reasons.</li>
						<li>Deposit will be used in the 7th billing month.</li>
					</ol>
				</td>
			</tr>			
			<tr><td colspan="2" >
				<input type="hidden" value="1"  name="registering"  />
				<input type="submit" value="SAVE"  class="inputButtonBox"  />
			</td></tr>
			</table>
		</form>
	</div>
</div>
</body>