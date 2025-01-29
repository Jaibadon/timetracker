
<body bgcolor="#FFFFFF" text="black">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <meta http-equiv="Content-Language" content="en-nz">
    <meta name="GENERATOR" content="Microsoft FrontPage 12.0">
    <meta name="ProgId" content="FrontPage.Editor.Document">
    <link href="invoice.css" rel="stylesheet" type="text/css">
    <title>CADviz_INV_{$invoice_id}</title>
    <style type="text/css">
        .style1 { text-align: left; }
        .style2 { text-align: right; }
        .style3 { background-color: #FFFFFF; font: Arial, Gadget, sans-serif; font-weight: bold; font-size: 30px; color: #000000; }
        .nrml { font-family: Verdana, Geneva, sans-serif; font-size: 9px; }
    </style>
</head>

<table>
    <tr class="style3">
        <td rowspan="4" valign="top"><img src="cadviz_logo_bw1.gif" width="200" height="89" alt="CADVIZ"></td>
        <td rowspan="4" class="style3"><div align="center">TAX INVOICE</div></td>
        <td><p align="right" class="nrml">&nbsp;</p></td>
    </tr>
    <tr class="style3">
        <td><div align="right"><strong><span class="nrml">GST No: 82-090-630</span></strong></div></td>
    </tr>
    <tr class="style3">
        <td><div align="right"><span class="nrml">CADViz Ltd</span></div></td>
    </tr>
    <tr class="style3">
        <td valign="top"><div align="right" class="nrml">
            <div align="right">PO Box 302387<br>North Harbour<br>Auckland 0751</div>
        </div></td>
    </tr>
</table>

<table>
    <tr>
        <td>Client:</td>
        <td>{$client_name}</td>
        <td>&nbsp;</td>
        <td>Invoice No:&nbsp;</td>
        <td><div align="right">{$invoice_id}</div></td>
    </tr>
    <tr>
        <td valign="top">Address:<br>Email:</td>
        <td valign="top">{$client_address}<br>{$billing_email}</td>
        <td>&nbsp;</td>
        <td valign="top">Date:&nbsp;</td>
        <td valign="top"><div align="right">{$invoice_date|date_format:"%d/%m/%Y"}</div></td>
    </tr>
</table>

<table border="1">
    <tr>
        <td>Date</td>
        <td>ID</td>
        <td>Item</td>
        <td>Hours/Qty</td>
        <td>Rate</td>
        <td>Subtotal</td>
    </tr>
    {foreach $invoice_items as $invoice_item}
    <tr>
        <td>{$invoice_item.date|date_format:"%d/%m/%Y"}</td>
        <td>{$invoice_item.user_name|escape}</td>
        <td>{$invoice_item.note|escape}</td>
        <td align="right">{$invoice_item.duration}</td>
        <td align="right">{$invoice_item.rate|number_format:2}</td>
        <td align="right">{$invoice_item.cost|number_format:2}</td>
    </tr>
    {/foreach}
</table>

<table>
    <tr>
        <td>Subtotal:</td>
        <td align="right">{$subtotal}</td>
    </tr>
    <tr>
        <td>GST:</td>
        <td align="right">$ {$tax|number_format:2}</td>
    </tr>
    <tr>
        <td><strong>Total:</strong></td>
        <td align="right"><strong>$ {$total|number_format:2}</strong></td>
    </tr>

<tr>
    <td><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment due by:&nbsp;
    {$invoice_due}
    </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=1>Thank you for your valued custom<font></font></font></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
</tr>
</table>

<table width="651" border="1">
  <tr>
    <td>
      <table width="651" border="0">
        <tr>
          <td width="27"><font size=4>&nbsp;</font></td>
          <td width="182" valign="top"><strong>REMITTANCE ADVICE</strong></td>
          <td width="255" valign="top"><font size=1>Please Detach this portion if posting your payment.</font></td>
          <td width="69"></td>
          <td width="96"></td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>Make cheques payable to:</td>
          <td>CADViz Ltd</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td> PO Box 302387, North Harbour <br>Auckland 0751</td>
          <td><div align="right">
            <strong>Total Due:</strong>
          </div></td>
          <td><div align="right">
            <font size="+1"><strong>
           $ {$total|number_format:2}
            </strong></font></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Internet Bank Transfer:</td>
          <td><strong> 03 0275 0551274 00</strong></td>
          <td>Reference:</td>
          <td><div align="right">
            {$invoice_id}
          </div></td>
        </tr>
        <td>&nbsp;</td>
        <td colspan="3">Please email any queries to:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=mailto:accounts@cadviz.co.nz>accounts@cadviz.co.nz</a></td>
        <td>&nbsp;</td>
      </tr>
    </td>
  </tr>
</table>
</body>

