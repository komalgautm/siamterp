@php
$detail = \App\ImportModel::leftJoin('items as it' , 'item', '=', 'it.id')
            ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
            ->select('imports.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit')
            ->where('po',$id)->get();
$vendor = \App\VendorModel::where('id',$vendor)->first();
$total = DB::table('imports')->where('po',$id)->sum('total_price');
$province = \App\ProvincesModel::select('name_th')->where('id',$vendor->provinces)->first();
$district = \App\DistrictModel::select('name_th')->where('id',$vendor->district)->first();
$subdistrict = \App\SubdistrictModel::select('name_th','zipcode')->where('id',$vendor->subdistrict)->first();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $code }}</title>
    <style>
        @page {
            /* size: 25cm 30cm portrait; */
            margin: 20px 20px;
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("fonts/THSarabunNew.ttf") format('TrueType');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("fonts/THSarabunNew Bold.ttf") format('TrueType');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("fonts/THSarabunNew Italic.ttf") format('TrueType');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("fonts/THSarabunNew BoldItalic.ttf") format('TrueType');
        }
        
        .line-T {
            border-top: solid #000 1px;
        }

        .line-B {
            border-bottom: solid #000 1px;
        }

        .line-L {
            border-left: solid #000 1px;
        }

        .line-R {
            border-right: solid #000 1px;
        }

        .line-B2 {
            border-bottom: #000 1px dotted;
        }

        .text-R {
            text-align: right;
        }

        body {
            margin: 0 auto;
            padding-top: 12px;
            color: #001028;
            background: #FFFFFF;
            font-size: 18px;
            font-family: 'THSarabunNew';
        }

        tr {
            line-height: 0.6;
        }

        .lineH {
            line-height: 0.2;
        }

        .lineH2 {
            line-height: 1;
            padding-top: 30px
        }

        .fontsize20 {
            font-size: 20px;
        }

        .fontsize16 {
            font-size: 16px;
        }

        .colorblue {
            color: #003366;
        }

        .bgblue {
            background-color: #222b35;
            color: white;
        }

        .bgwhite {
            background-color: white;
            color: black;
        }

        /* Customer */
        #customers {
            /* font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; */
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #d9e1f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }

        .hrclass {
            background-color: #002060;
            padding: 2px 2px;
        }

        .hrclass1 {
            background-color: #002060;
        }
        tfoot td {
            border: none!important;
        }
        footer {
            position: fixed;
            bottom: -1px;
            left: 0px; right: 0px;
            height: 110px; 

            /** Extra personal styles **/
            line-height: 10px;
        }
    </style>
</head>


<body>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:1%;">
        <tr>
            <td valign="top" style="width:100px;"><img src="upload/logo.jpg" alt="" style="width:100px;"></td>
            <td align="left" valign="top" style="padding-left:5px; width:500px;">
                <b class="fontsize20 colorblue" style="font-size:30px;">Siam Eats Co.,Ltd</b> <br />
                <span class="fontsize16 colorblue">16/18 Mu 11 Khlong Nueng, Khlong Luang Pathumthani 12120</span> <br />
                <span class="fontsize16 colorblue">Tax ID 395561000010</span>
            </td>
        </tr>
    </table>

    <hr class="hrclass" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:5%;">
        <tr>
            <td align="center" style="font-size:30px;" class="colorblue"><b>Purchase Order {{ $code }}</b></td>
        </tr>
    </table>
    <hr class="hrclass" />

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; ">
        <tr class="colorblue" style="font-size:18px;">
            <td colspan="6" >Vendor Info</td>
            <td colspan="6" >Order History</td>
        </tr>
    </table>
    <hr class="hrclass1" />

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; ">
        <tr class="colorblue" style="font-size:16px;">
            <td colspan="6" style="vertical-align: top;">
                <span><b>Name :</b> {{ @$vendor->name }}({{ @$vendor->id_card }})</span> <br>
                <span><b>Address :</b> {!! @$vendor->address !!} <br> {{ @$subdistrict->name_th.@$district->name_th.@$province->name_th." ".@$subdistrict->zipcode }}</span> <br>
                <span><b>Contact :</b> {{ @$vendor->phone }} / {{ @$vendor->email }}</span> <br>
                <span><b>Line ID :</b> {{ @$vendor->line_id }}</span>
            </td>
            <td colspan="6" style="vertical-align: top;">
                <span><b>Create Date :</b> {{ date('d-M-Y H:i:s',strtotime($created)) }}</span> <br>
                @if($pickup_date != '')<span><b>Pick Up Date :</b> {{ date('d-M-Y H:i:s',strtotime($pickup_date)) }}</span> <br>@endif
                @if($delivery_date != '')<span><b>Delivery Date :</b> {{ date('d-M-Y H:i:s',strtotime($delivery_date)) }}</span> <br>@endif
                @if($receive_date != '')<span><b>Receive Date :</b> {{ date('d-M-Y H:i:s',strtotime($receive_date)) }}</span> @endif
            </td>
        </tr>
    </table>

    <table id="customers" width="100%" border="0" cellpadding="0" cellspacing="0" style="">
        <!-- ส่วนหัว -->
        <thead>
            <tr>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>#</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:200px"> <b>Produce Detail</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>QTY</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>UNIT</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>Price</b></td>
           
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>Vat</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>Total</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>Wht</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; "><b>Crate</b></td>
            </tr>
        </thead>
        <!-- สิ้นสุดสัวนหัว -->
        <tbody>
            @foreach ($detail as $key => $de)
                <tr style="font-size:16px; line-height: 0.1;">
                    <td align="center">{{ $key+1 }}</td>
                    <td align="left">{{ $de->name_th_item.'/'.$de->name_en_item }}</td>
                    <td align="center">{{ $de->quantity }}</td>
                    <td align="center">{{ $de->name_th_unit.'/'.$de->name_en_unit }}</td>
                    <td align="center">{{ number_format($de->price) }}</td>
                    <td align="right">{{ $de->vat }}</td>
                    <td align="right">{{ number_format($de->total_price) }}</td>
                    <td align="right">{{ $de->wht }}</td>
                    <td align="right">{{ $de->crate }}</td>
                </tr>
            @endforeach 
        </tbody>
    </table>
    <table width="100%">
        <tfoot>
            <tr style="line-height: 0.4;">
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px;"><b>TOTAL THB</b></td>
            </tr>
            <tr style="line-height: 0.4;">
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">&nbsp;</td>
                <td align="right" class="line-R bgwhite" style="font-size:18px; padding:2px 2px;"><b>{{ number_format($total,4) }}</b></td>
            </tr>
        </tfoot>
    </table>


    <footer class="colorblue">
        <span><b>Payment</b></span> <br>
        <span><b>Paid On :</b> {{ date('d-M-Y',strtotime($paid_date)) }}</span> <br>
        <span><b>Totals :</b> {{ $total }} THB</span> <br>
        <span><b>Bank Name :</b> {{ @$vendor->bank_name }}</span> <br>
        <span><b>Account Name :</b> {{ @$vendor->bank_account }}</span> <br>
        <span><b>Account Number :</b> {{ @$vendor->bank_number }}</span> <br>
        <span><b>Paid With :</b> @if($paid_by != 'staff'){{ $paid_by }} @else {{ $staff_name }} @endif</span>
    </footer>
</body>
</html>
