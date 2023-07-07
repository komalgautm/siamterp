@php
    $quotation = \App\QuotationModel::where('id',$quotation_id)->first();
    $detail = \App\OperationModel::select('operation.box','operation.packaging','detail.*','itf.name')->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->leftJoin('itf','detail.itf_id','=','itf.id')
            ->where('operation.order_id',$id)->get();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $code }} Operation List</title>
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

        body {
            /* margin: 0 0 0 0; */
            /* padding-top: 5px; */
            color: #001028;
            background: #FFFFFF;
            font-size: 18px;
            font-family: 'THSarabunNew';
        }

        tr {
            line-height: 0.6;
        }

        tr.list {
            line-height: 0.1;
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

        /* Customer */
        #customers {
            /* font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; */
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 10px;
        }

        #customers tr:nth-child(even) {
            background-color: #d9e1f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }

        .hrclass {
            background-color: #222b35;
            padding: 1px 1px 1px 1px;
        }
    </style>
</head>


<body>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px;">
        <tr>
            <td valign="top" style="width:150px;"><img src="upload/logo.jpg" alt="" style="width:125px;"></td>
            <td align="left" valign="top" style="padding-left:10px; padding-right:10px; width:150px; line-height:0.8;">
                <b class="fontsize20 colorblue">Siam Eats Co.,Ltd</b> <br />
                <span class="fontsize16 colorblue">16/18 Mu 11</span> <br />
                <span class="fontsize16 colorblue">Khlong Nueng, Khlong Luang</span> <br />
                <span class="fontsize16 colorblue"Pathumthani 12120, Thailand</span> <br />
                <span class="fontsize16 colorblue">Tax ID 395561000010</span> <br />
            </td>
            <td align="left">
                <table width="100%">
                    <tbody style="font-size:16px;">
                        <tr>
                            <td align="center" colspan="6" class="bgblue" style="padding: 5px 5px; font-size: 20px;"><b>ออเดอร์ /โหลด</b></td>
                        </tr>
                        <tr>
                            <td>เลขออเดอร์ #</td>
                            <td>:</td>
                            <td>{{ $code }}</td>
                            <td>วันที่</td>
                            <td>:</td>
                            <td>@if($load_date != ''){{ date('d-M-Y',strtotime($load_date)) }}@endif</td>
                        </tr>
                        <tr>
                            <td>เลขสั่งซื้อ</td>
                            <td>:</td>
                            <td>{{ $tt_ref}} - {{ $client->name }}</td>
                            <td>เวลาโหลด</td>
                            <td>:</td>
                            <td>@if($load_time != ''){{ date('H:i',strtotime($load_time)) }}@endif</td>
                        </tr>
                        <tr>
                            <td>AWB</td>
                            <td>:</td>
                            <td>{{ $awb_no }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <hr class="hrclass" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>


    {{-- <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px; ">
        <tr>
            <td>&nbsp;&nbsp;</td>
        </tr>
    </table> --}}

    <table id="customers" width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px; ">
        <!-- ส่วนหัว -->
        <thead>
            <tr>
                <td colspan="3" align="center" class="line-R bgblue" style="padding: 5px 5px; font-size:20px;">
                    <b>รายละเอียดสินค้า</b></td>
                <td colspan="3" align="center" class="line-R bgblue" style="padding: 5px 5px; font-size:20px;">
                    <b>ออเดอร์</b></td>
                <td colspan="2" align="center" class="line-R bgblue" style="padding: 5px 5px; font-size:20px;">
                    <b>โหลด</b></td>
            </tr>
            <tr>
                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px; width:300px;">
                    <b>สินค้า</b></td>
                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
                    <b>แพ็ค</b></td>
                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
		    <b>ใส่กล่อง</b>
                </td>

                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
                    <b>กก.</b></td>
                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
                    <b>กล่อง</b></td>
                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
                    <b>แพ็ค/ชิ้น</b></td>

                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
                    <b>กล่อง</b></td>
                <td align="center" class="line-T line-L line-R line-B bgblue" style="padding: 5px 5px; font-size:18px;">
                    <b>พาเลท</b></td>
            </tr>
        </thead>
        <!-- สิ้นสุดสัวนหัว -->
        <tbody>
            @foreach($detail as $de)
            @php
                $item = \App\ITFdetailModel::where(['type'=>'ean','itf'=>$de->itf_id])->first()->item;
                $ean_qty = \App\SetupModel::where(['type'=>'item','product'=>$item])->first()->qty;

		$setup_product = \App\SetupModel::where(['type'=>'packaging', 'product'=>$item])->first();
		$pkg_name="";
		if ( $setup_product) {
			$pkg_item = \App\SetupModel::where(['type'=>'packaging', 'product'=>$item])->first();
			if ($pkg_item ) {
				$pkg_item_id = $pkg_item->item;
				$pkg_name = \App\ItemModel::where(['id'=>$pkg_item_id])->first()->name_th;
			}else{
				$pkg_name="";
			}
		}


		$boxes_item_id = \App\ITFdetailModel::where(['type'=>'boxes','itf'=>$de->itf_id])->first()->item;
		$box_name = \App\ItemModel::where(['id'=>$boxes_item_id])->first()->name_th;

		$name_without_english = explode('/',$de->name)[0];
		$name_with_english = $de->name;
		$exploded = explode(" - ", $de->name);
		$data = end($exploded);
            @endphp
            <tr class="list">
                <td align="left" class="line-T line-R line-B" style="font-size:18px;">{{ $name_with_english }}</td>

<!--
                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $ean_qty }} g</td>
                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $de->ean_qty }}</td>
-->

                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $pkg_name }}</td>
                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;white-space: pre;">{{ $box_name }}</td>

                <td align="center" class="line-T line-R line-B" style="font-size:18px;">{{ $de->nw }}</td>
                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $de->number_box }}</td>
                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $de->ean_qty*$de->number_box }}</td>

                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $de->box }}</td>
                <td align="center" class="line-T line-L line-R line-B" style="font-size:18px;">{{ $de->packaging }}</td>
            </tr>
<!--
            <tr>
<td colspan="8">
<pre>
@php
print_r($de);
@endphp
</pre>
</td>
	</tr>
-->
            @endforeach
        </tbody>
    </table>


</body>

</html>
