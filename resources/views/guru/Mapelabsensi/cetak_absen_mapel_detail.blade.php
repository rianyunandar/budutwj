
<style type="text/css">

@font-face {
    font-family: MyBarcode;
    src: url(barcode.woff)
}

* {
    margin: 0;
    padding: 0;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box
}

.page {
    position: relative;
    width: 21cm;
    min-height: 29cm;
    page-break-after: always;
    margin: 0.5cm auto;
    background: #FFF;
    padding: 1.5cm;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    -webkit-box-sizing: initial;
    -moz-box-sizing: initial;
    box-sizing: initial;
    page-break-after: always
}

.page * {
    font-family: arial;
    font-size: 11px
}

.page-landscape {
    position: relative;
    width: 29.7cm;
    min-height: 21cm;
    page-break-after: always;
    margin: 0.5cm;
    background: #FFF;
    padding: 1.5cm;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    -webkit-box-sizing: initial;
    -moz-box-sizing: initial;
    box-sizing: initial;
    page-break-after: always
}

.page-landscape * {
    font-family: arial;
    font-size: 11px
}

.footer {
    position: absolute;
    bottom: 1.5cm;
    left: 1.5cm;
    right: 1.5cm;
    width: auto;
    height: 30px
}

.kanan {
    float: right
}

.barcode {
    font-family: MyBarcode
}

.it-grid {
    background: #FFF;
    border-collapse: collapse;
    border: 1px solid #000
}

.it-grid th {
    color: #000;
    border: 1px solid #000;
    border-top: 1px solid #000;
    background: #C4BC96;
    padding: 3px;
    border: 1px solid #000
}

.it-grid tr:nth-child(even) {
    background: #f8f8f8
}

.it-grid td {
    padding: 3px;
    border: 1px solid #000
}

.seri {
    font-family: 'Lucida Handwriting'
}

.it-cetak td {
    padding: 6px 5px
}

h1 {
    font-weight: normal
}

h2 {
    font-weight: normal
}

h3 {
    font-weight: normal
}

h4 {
    font-weight: normal
}

h5 {
    font-weight: normal
}

h6 {
    font-weight: normal
}

table {
    border-collapse: collapse;
    page-break-inside: auto
}

td {
    padding: 3px
}

.f14 {
    font-size: 14pt
}

.f12 {
    font-size: 12pt
}


.line-bottom {
    border-bottom: 1px solid black
}

.detail {
    margin-top: 10px;
    margin-bottom: 10px
}

.detail td {
    padding: 5px;
    font-size: 12px
}

.detail span {
    border-bottom: 1px solid black;
    display: inline-block;
    font-size: 12px
}

.cetakan {
    font-size: 14px;
    line-height: 1.5em
}

.cetakan * {
    font-size: 14px;
    line-height: 1.5em
}

.cetakan span {
    border-bottom: 1px solid black;
    display: inline-block
}

.full {
    width: 100%
}

nip {
    display: inline-block;
    width: 130px
}

a {
    text-decoration: none;
    color: #006600
}

ol {
    margin-left: 30px
}

ol>li {
    padding: 10px
}

tr {
    page-break-inside: avoid;
    page-break-after: auto
}

thead {
    display: table-row-group
}

tfoot {
    display: table-row-group
}

.table th,
.table td {
    padding: 5px
}

.table tbody tr:nth-child(even) {
    background: #EEE
}

.table thead {
    background: #ccc
}

@media print {
    body {
        background: #fff;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt
    }
    div {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt
    }
    td {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt
    }
    p {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt
    }
    .page {
        height: 10cm;
        padding: 0.7cm;
        box-shadow: none;
        margin: 0
    }
    @page {
        size: A4;
        margin: 0;
        -webkit-print-color-adjust: exact
    }
    .page-landscape {
        height: 10cm;
        padding: 0.7cm;
        box-shadow: none;
        margin: 0
    }
    .footer {
        bottom: 0.7cm;
        left: 0.7cm;
        right: 0.7cm
    }
}
</style>
<style type="text/css">
    @media print{@page {size: landscape}}
    .landscape{
        margin-top: 20px;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 20px;
    }
    .title2{
        font-size: 30px;
    }
    .border-image {
  border: 1px solid black;
  border-radius: 10px;
}
</style>
<div class="landscape">
	<!--/cover header-->
	<table width='100%' border="0">
		<tr>
			<td width='120'><img src='{{ asset('image/logo_tut.svg') }}' height='100'></td>
			<td>
				<CENTER>
					<strong class='f12' style="font-size: 25px;">
						{{ $judul }} <br />
						{{ strtoupper($mapel) }}<br />
						{{ strtoupper($sekolah) }}
						<br>
						{{ $ajaran }}
					</strong>
				</CENTER></td>
			<td width='120'><img src='{{ asset('image/budut.png') }}' height='100'></td>
		</tr>
	</table>
	<br>
	<hr>
	<!--/cover header-->
	<table class='detail' border="0">
		{{-- <tr>
			<td>TAHUN</td><td>:</td><td><span style='width:450px;'>&nbsp; 2021</span></td>
			<td rowspan="3" width="400"></td>
			<td rowspan="3"><img class="border-image" width="100" src=""></td>
		</tr> --}}
		<tr>
			<td>BULAN</td><td>:</td><td><span style='width:450px;'>&nbsp; {{ $bulan }}</span></td>
			
		</tr>
		<tr>
			<td width="170">MATA PELAJARAN</td><td>:</td><td ><span style='width:450px;'>&nbsp; {{ $mapel }}</span></td>
			
		</tr>
		<tr>
			<td >KELAS</td><td>:</td><td ><span style='width:450px;'>&nbsp; {{ $rombel }}</span></td>
			
		</tr>
	</table>
	
	<table class='it-grid it-cetak' width='100%' >
		<thead style="background-color: #c7c7c7; font-weight: bold;" >
		<tr height=40px>
			<th width='2%' align=center>No</th>
			<th  width='25%'>NAMA</th>
			<?php for ($i=1; $i <=31 ; $i++) { 
				echo"<th width='2%'>".$i."</th>";
			} ?>
			<th width='1%'>H</th>
			<th width='1%'>A</th>
			<!-- <th width='1%'>B</th> -->
			<th width='1%'>I</th>
			<th width='1%'>T</th>
			<th width='1%'>S</th>
			{{-- <th width='1%'>U</th>
			<th width='1%'>L</th>
			<th width='1%'>K</th> --}}
			<!-- <th width='4%'>%</th> -->
			</tr>
		</thead>
		<tbody>
			<?php $no=1; ?>
			@foreach ($absen as $data)
			<tr>
				<td>{{ $no++ }}</td></td>
				<td>{{ $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName }}</td> 
				@for ($i=1; $i <=31 ; $i++)
					<td align="center">{{ $data['tgl'.$i] }}</td>
				@endfor
				<td align="center">{{ $data->HADIR }}</td>
				<td align="center">{{ $data->ALPHA }}</td>
				<td align="center">{{ $data->IZIN }}</td>
				<td align="center">{{ $data->TERLAMBAT }}</td>
				<td align="center">{{ $data->SAKIT }}</td>
				{{-- <td align="center">{{ $data->ULANGAN }}</td>
				<td align="center">{{ $data->LIBUR }}</td>
				<td align="center">{{ $data->KEGIATAN }}</td> --}}
			</tr>
			@endforeach
		</tbody>
	</table>
	<!-- BAGIAN TANDA TANGGAN -->
	<div style='padding-left: 50px;'>
		<style type="text/css">
			.panjang{
				width: 180px;
			}
			
		</style>
		<br><br>
		<table border='0' width='100%'>
			<tr>
				<td ></td>
				<td >Kecamatan, 22 Januari 2021</td>
			</tr>
			
			<tr>
				<td></td>
				<td>Guru Mapel,</td><!-- proktor -->
			</tr>
			<tr>
				<td class="panjang"><br><br><br><strong></strong></td>
				<td class="panjang"><br><br><br><strong>Nama Guru</strong></td>
			</tr>
			<tr>
				<td></td>
				<td >NIP.123</td>
			</tr>
		</table>
	</div>
	<!-- /BAGIAN TANDA TANGGAN -->
	
</div>
			
</div>
