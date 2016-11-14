<table  width="100%">
    <tr>
        <td>
            <a href="">
                <img src="assets/img/themusthaves.png" width="300px">
            </a>
            <br>

        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>
                        {{  $billing_info['first_name'] }}
                    </td>
                    <td>
                        {{  $billing_info['last_name'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{  $billing_info['address_1'] }} <br>
                        {{  $billing_info['address_2'] }}
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        {{  $billing_info['postcode'] }}
                    </td>
                    <td>
                        {{  $billing_info['city'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{  $billing_info['country'] }}
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        {{  $billing_info['email'] }}
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Factuurnummer</td>
                    <td>{{  $id }}</td>
                </tr>
                <tr>
                    <td>Factuurdatum</td>
                    <td>{{  date('d F Y', strtotime($created_at)) }}</td>
                </tr>
            </table>
        </td>
        <td>
            &nbsp;
        </td>
        <td>
            <table>
                <tr>
                    <td> {{  Config::get('connection') }}</td>
                </tr>
                <tr>
                    <td>Glidenveld 21</td>
                </tr>
                <tr>
                    <td>3892DC Zeewolde</td>
                </tr>
                <tr>
                    <td>58655913</td>
                </tr>
                <tr>
                    <td>BTW-nr. Netherland: NL853127244B01</td>
                </tr>
                <tr>
                    <td>BTW-nr. Belgie: BE0552666210</td>
                </tr>

            </table>
        </td>
    </tr>

</table>
<br><br><br>
<table  width="100%">
    <tr>
        <th>
            Titel
        </th>
        <th>
            Aantal
        </th>
        <th>
            Btw%
        </th>
        <th style="text-align: right !important;">
            Prijis incl. btw
        </th>
    </tr>
    <?php
        $total = 0 ;
    ?>
    @foreach($order_items as $item)
    <tr>
        <td>
            {{--{{ $products[$item['product_id']]['name'] }}--}}
            {{ $item['product_id'] }}
        </td>
        <td>
            {{ $item['total'] }}
        </td>
        <td>
            {21%}
        </td>
        <td style="text-align: right">
            {{ $item['total'] }}
        </td>
    </tr>
        <?php
            $total += $item['total'];
        ?>
    @endforeach
</table>
<br>
<br>
<br>
<table width="100%">
    <tr>
        <td width="75%" style="text-align: right">
            Subtotaal
        </td>
        <td width="25%" style="text-align: right">
            {{ $total }}
        </td>
    </tr>
    <tr>
        <td width="75%" style="text-align: right">
            Btw 21%
        </td>
        <td width="25%" style="text-align: right">
            btw
        </td>
    </tr>
    <tr>
        <td width="75%" style="text-align: right">
            Totaal
        </td>
        <td width="25%" style="text-align: right">
            {{ $total }}
        </td>
    </tr>
</table>

<style>
    table th {
        text-align: left !important;
    }
    table td{
        padding: 5px 0;
        border:none;
        font-family: 'Roboto', 'Noto', sans-serif;
        font-size: 13px;
    }
</style>