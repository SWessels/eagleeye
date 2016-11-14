<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>themusthaves </title>
    <style type="text/css">


        /* ------------------------------------- 		GLOBAL ------------------------------------- */
        * {
            margin:0;
            padding:0;
        }
        * {
            font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
        }
        img {
            max-width:100%;
        }
        .collapse {
            padding-right:15px;
            padding:0;
        }
        body {
            -webkit-font-smoothing:antialiased;
            -webkit-text-size-adjust:none;
            width:100%!important;
            height: 100%;
        }
        /* ------------------------------------- 		ELEMENTS ------------------------------------- */
        a {
            color:#362b29;
            font-size:12px;
        }
        .bt {
            padding-top:10px;
        }
        p.callout {
            padding:9px;
            font-size:12px;
        }
        p.text {
            padding-left:5px;
            font-size:12px;
        }
        p.left {
            padding:5px;
            font-size:12px;
            text-align:left;
        }
        .prod {
            margin:0;
            padding:0;
            color:#aaaaaa;
        }
        .callout a {
            font-weight:bold;
            color: #aaaaaa;
        }
        /* ------------------------------------- 		HEADER ------------------------------------- */
        table.head-wrap {
            width:100%;
        }
        .header.container table td.logo {
            padding:15px;
        }
        .header.container table td.label {
            padding:15px;
            padding-left: 0px;
        }
        /* ------------------------------------- 		BODY ------------------------------------- */
        table.body-wrap {
            width: 100%;
        }
        /* ------------------------------------- FOOTER------------------------------------- */
        table.footer-wrap {
            width:100%;
            background-color: #f5f5f5;
            height: 50px;
        }
        table.footer-wrap2 {
            width: 100%;
        }
        }
        /* ------------------------------------- 		TYPOGRAPHY ------------------------------------- */
        h1,h2,h3,h4,h5,h6 {
            font-family:"Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
            line-height:1.1;
            margin-bottom:5px;
            color:#000;
        }
        h1 small,h2 small,h3 small,h4 small,h5 small,h6 small {
            font-size:60%;
            color:#6f6f6f;
            line-height:0;
            text-transform:none;
        }
        h1 {
            font-weight:200;
            font-size:18px;
            padding:20px;
            letter-spacing:3px;
            font-weight:300;
        }
        h2 {
            font-weight:200;
            font-size:37px;
        }
        h3 {
            font-weight:500;
            font-size:27px;
        }
        h4 {
            font-weight:500;
            font-size:23px;
        }
        h5 {
            font-weight:900;
            font-size:13px;
            color:#c2a67e;
        }
        h6 {
            font-weight:900;
            font-size:14px;
            text-transform:uppercase;
            color:#444;

        }
        h7 {
            font-weight:900;
            font-size:14px;
            text-transform:uppercase;
            color:#444;
            padding:5px;
        }
        .collapse {
            margin:0!important;
        }
        p,ul {
            margin-bottom:2px;
            font-weight:normal;
            font-size:13px;
            line-height:1.6;
        }
        p.lead {
            font-size:13px;
        }
        p.last {
            margin-bottom:0px;
        }
        ul li {
            margin-left:5px;
            list-style-position: inside;
        }
        /* --------------------------------------------------- 		RESPONSIVENESS		Nuke it from orbit. ------------------------------------------------------ */
        /* Set a max-width,and make it display as block so it will automatically stretch to that width,but will also shrink down on a phone or something */
        .container {
            display:block!important;
            max-width:700px!important;
            margin:0 auto!important;
            /* makes it centered */
            clear:both!important;
            padding: 10px;
        }
        /* This should also be a block element,so that it will fill 100% of the .container */
        .content {

            max-width:700px;
            margin:0 auto;
            display: block;
        }

        /* Let's make sure tables in the content area are 100% wide */
        .content table {
            width: 100%;
        }
        /* Odds and ends */
        .column {
            width:300px;
            float:left;
        }
        .column tr td {
            padding:5px;
        }
        .column-wrap {
            padding:0!important;
            margin:0 auto;
            max-width:700px!important;
        }
        .column table {
            width:100%;
        }
        .social .column {
            width:280px;
            min-width:279px;
            float:left;
        }
        .column3 {
            width:300px;
            float:left;
        }
        .column3 tr td {
            padding:1px;
        }
        .column3-wrap {
            padding:0!important;
            margin:0 auto;
            max-width:700px!important;
        }
        .column3 table {
            width:100%;
        }
        .column2 {
            width:240px;
            float:left;
        }
        .column2 tr td {
            padding:5px;
        }
        .column2-wrap {
            padding:0!important;
            margin:0 auto;
            max-width:700px!important;
        }
        .column2 table {
            width:100%;
        }
        .social .column {
            width:280px;
            min-width:279px;
            float: left;
        }
        /* Odds and ends */
        .prod {
            width:200px;
            float:left;
        }
        .prod tr td {
            /*padding:5px;*/
        }
        .prod-wrap {
            padding:0!important;
            margin:0 auto;
            max-width:700px!important;
        }
        .prod table {
            width:100%;
        }
        .prod .column {
            width:200px;
            min-width:200px;
            float: left;
        }
        /* Be sure to place a .clear element after each set of columns,just to be safe */
        .clear {
            display:block;
            clear: both;
        }
        /* ------------------------------------------- 		PHONE		For clients that support media queries.		Nothing fancy. -------------------------------------------- */
        @media only screen and (max-width:700px) {
            a[class="btn"] {
                display:block!important;
                margin-bottom:10px!important;
                background-image:none!important;
                margin-right:0!important;
            }
            div[class="column"] {
                width:auto!important;
                float:none!important;
            }
            div[class="column2"] {
                width:auto!important;
                float:none!important;
            }
            div[class="column3"] {
                width:auto!important;
                float:none!important;
            }
            table[class="top"] {
                width:auto!important;
                float:none!important;
            }
            .prod {
                width: 310px;
                float:left;
            }
            table.social div[class="column"] {
                width: auto!important;
            }
        }
    </style>
</head>

<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  >
<center>
    <!------------------------------------ ---- HEADER -------------------------- ------------------------------------->
    <table class="head-wrap" bgcolor="#FFFFFF">
        <tr>

            <td class="header container" style="    border: 2px solid rgba(170, 170, 170, 0.27); display: block!important; max-width: 700px!important; margin: 0 auto!important;  clear: both!important; padding: 10px;">
                <div class="content" style="border: 2px solid rgba(170, 170, 170, 0.27);  ">
                    <table bgcolor="#FFFFFF" class="" style="width: 100%">
                        <tr>
                            <td align="center">
                                <img src="http://fashionhomerun.nl/assets/img/email/logo.jpg"/>
                            </td>

                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center" style="border-top: 2px solid rgba(170, 170, 170, 0.27); padding: 5px 0;">
                                <a href="{{ $site_url }}" style="padding: 10px; text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Home</a>
                                <a href="{{ $site_url.'/nieuwe'  }}" style="padding: 10px;    text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Nieuw</a>
                                <a href="{{ $site_url.'/alle-kleding'  }}" style=" padding: 10px;   text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Kleding</a>
                                <a href="{{ $site_url.'/alle-accessoires'  }}" style=" padding: 10px;   text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Accessories</a>
                                <a href="{{ $site_url.'/alle-schoenen'  }}" style=" padding: 10px;   text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Shoenen</a>
                                <a href="{{ $site_url.'/musthave-deals'  }}" style=" padding: 10px;   text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Musthave Deals</a>
                                <a href="{{ $site_url.'/sale'  }}" style=" padding: 10px;   text-decoration: none; color: #aaaaaa; font-size: 1.2em; font-weight: normal; font-family: Verdana;">Sale</a>
                            </td>
                        </tr>
                    </table>
                </div>


                <!-- content -->

                <!-- COLUMN WRAP -->
                <div class="column-wrap">

                    <div class="content">
                        <!-- Line -->
                        <table>

                            <!-- DIVIDER TITLE -->
                            <td align="center" valign="middle">

                                <tr>
                                    <td>
                                        <p style="font-size: 1em;">
                                            <strong> Tryout Product likes reached 100 within 10 days.</strong>
                                            <br>
                                            <br>
                                            Name : <strong> {{ $product_name }}</strong>
                                            <br>
                                            <br>
                                            Price: <strong> {{ $product_price }} </strong>
                                            <br>
                                            <br>
                                           link: <a href="{{ $product_url }}">{{ $product_name }}</a>

                                        </p>

                                    </td>
                                </tr>
                            </td>
                            <td>

                            </td>
                        </table>
                    </div>
                    <!------------------------------------ ---- PRODUCT LINE ONE -------------------------- ------------------------------------->



                </div>
                <!------------------------------------ ---- BTN IPHONE -------------------------- ------------------------------------->

                <!------------------------------------ ---- BOTTOM BANNER -------------------------- ------------------------------------->

                <div class="clear">
                </div>


            </td>
            <td>
            </td>
        </tr>
    </table>
</center>
</body>
</html>